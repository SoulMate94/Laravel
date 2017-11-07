<?php

将系统提示由中文转英文

====================resources/sys_msg/en/Main.php============================

<?php

return [
    'UNAUTHORIZED'         => 'Unauthorized',
    'UNAUTHORIZED_AKSK'    => 'Unauthorized API call',
    'NO_USER'              => 'User not found',
    'UPDATE_USER_FAILED'   => 'Update user information failed',
    ...
];


====================resources/sys_msg/zh/Main.php============================

<?php

return [
    'UNAUTHORIZED'         => '未登录或登录信息已过期',
    'UNAUTHORIZED_AKSK'    => '授权失败',
    'NO_USER'              => '找不到用户',
    'UPDATE_USER_FAILED'   => '更新用户信息失败',
    'USER_SECURE_PASSWORD_ILLEGAL' => '安全密码错误',
    ...
];


====================app/Http/Controllers/System/Message.php====================
<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;

class Message implements \ArrayAccess
{
    public $lang = null;
    public $text = [];

    protected function path()
    {
        return resource_path().'/sys_msg/'.$this->lang;
    }

    public function get(Request $req)
    {
        $this->lang = $req->get('lang') ?? $this->getDefaultLang();

        return response()->json([
            'err' => 0,
            'msg' => 'ok',
            'dat' => $this->load(),
        ]);
    }

    public function offsetExists($offset)
    {
        return isset($this->text[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->text[$offset])
        ? $this->text[$offset]
        : (
            ('zh' == $this->lang)
            ? '服务繁忙，请稍后再试'
            : 'Service is busy or temporarily unavailable.'
        );
    }

    public function offsetSet($offset, $value): void
    {
    }

    public function offsetUnset($offset): void
    {
    }

    public function msg($lang): self
    {
        $this->load($lang);

        return $this;
    }

    public function load($lang = null)
    {
        if (! $this->lang) {
            $this->lang = $lang ?? $this->getDefaultLang();
        }

        $dat = [];

        if ($fsi = $this->getFilesystemIterator()) {
            foreach ($fsi as $file) {
                if ($file->isFile() && 'php' == $file->getExtension()) {
                    $_dat = include_once $file->getPathname();
                    if ($_dat && is_array($_dat)) {
                        $dat = array_merge($_dat, $dat);
                    }
                }
            }
        }

        return $this->text = $dat;
    }

    protected function getDefaultLang(): string
    {
        return 'zh';
    }

    protected function getFilesystemIterator()
    {
        if (($path = $this->path()) && file_exists($path)) {
            return new \FilesystemIterator($path);
        }

        return false;
    }
}



==========================app/Traits/Tool.php==================================
<?php



namespace App\Traits;

class Tool
{

    public static function sysMsg($key, $lang = 'zh')
    {
        $lang = $_REQUEST['lang'] ?? 'zh';

        if (isset($GLOBALS['__sys_msg'])
            && is_array($GLOBALS['__sys_msg'])
            && $GLOBALS['__sys_msg']
        ) {
            $msg = $GLOBALS['__sys_msg'];
        } else {
            $msg = [];
            $path = resource_path().'/sys_msg/'.$lang;
            if (file_exists($path)) {
                $fsi = new \FilesystemIterator($path);
                foreach ($fsi as $file) {
                    if ($file->isFile() && 'php' == $file->getExtension()) {
                        $_msg = include $file->getPathname();
                        if ($_msg && is_array($_msg)) {
                            $msg = array_merge($_msg, $msg);
                        }
                    }
                }

                $GLOBALS['__sys_msg'] = $msg;
            }
        }

        return $msg[$key]
        ?? (
            ('zh' == $lang)
            ? '服务繁忙，请稍后再试'
            : 'Service is busy or temporarily unavailable.'
        );
    }
}
