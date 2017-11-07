<?php

// Aliyun SMS Service
// See: <https://help.aliyun.com/document_detail/55451.html?spm=5176.doc55289.6.556.pMlBIe>

==========================短信发送API==========================================
发送短信接口(SendSms)
步骤 1 创建阿里云账号
步骤 2 获取阿里云访问密钥
步骤 3 在控制台完成模板与签名的申请，获得调用接口必备的参数
    短信签名
    短信模板

为了成功发送一条短信通知，您至少需要完成以下步骤
一、在控制台完成短信签名与短信模板的申请，获得调用接口必备的参数
在“短信签名”页面完成签名的申请，获得短信签名的字符串
在“短信模板”页面完成模板的申请，获得模板ID。


===编写样例程序
// 此处需要替换成自己的AK信息
    $accessKeyId = "yourAccessKeyId";//参考本文档步骤2
    $accessKeySecret = "yourAccessKeySecret";//参考本文档步骤2

    //短信API产品名（短信产品名固定，无需修改）
    $product = "Dysmsapi";

    //短信API产品域名（接口地址固定，无需修改）
    $domain = "dysmsapi.aliyuncs.com";

    //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
    $region = "cn-hangzhou";

    //初始化访问的acsCleint
    $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
    DefaultProfile::addEndpoint(
        "cn-hangzhou", "cn-hangzhou", $product, $domain
    );
    $acsClient= new DefaultAcsClient($profile);

     $request = new Dysmsapi\Request\V20170525\SendSmsRequest;
    //必填-短信接收号码。支持以逗号分隔的形式进行批量调用，批量上限为1000个手机号码,批量调用相对于单条调用及时性稍有延迟,验证码类型的短信推荐使用单条调用的方式
    $request->setPhoneNumbers("15067126468");

    //必填-短信签名
    $request->setSignName("云通信");

    //必填-短信模板Code
    $request->setTemplateCode("SMS_0001");

    //选填-假如模板中存在变量需要替换则为必填(JSON格式),友情提示:如果JSON中需要带换行符,请参照标准的JSON协议对换行符的要求,比如短信内容中包含\r\n的情况在JSON中需要表示成\\r\\n,否则会导致JSON在服务端解析失败
    $request->setTemplateParam(
        "{\"code\":\"12345\",\"product\":\"云通信服务\"}"
    );

    //选填-发送短信流水号
    $request->setOutId("1234");

    //发起访问请求
    $acsResponse = $acsClient->getAcsResponse($request);


========================Lumen.AliyunSMS========================================
<?php

namespace App\Http\Controllers\ThirdParty;

use
    Laravel\Lumen\Routing\Controller,
    Illuminate\Http\Request,
    App\Traits\Tool,
    Flc\Dysms\Client,
    Flc\Dysms\Request\SendSms;

class AliyunSMS extends Controller
{
    protected $tmpl     = null;
    protected $config   = null;
    protected $mobile   = null;

    public function __construct($config = [])
    {
        $this->config = $config;
        if (!$config && !($this->config = config('custom')['aliyun_sms'])) {
            throw new \Exception('Missing aliyun sms configs.');
        }
        if (! $this->legalCfg('akid')) {
            throw new \Exception('Missing aliyun sms api access key id.');
        }
        if (! $this->legalCfg('aksk')) {
            throw new \Exception('Missing aliyun sms api access key secret.');
        }
        if (! $this->legalCfg('sign')) {
            throw new \Exception('Missing aliyun sms api sign name.');
        }
        if (! isset($this->config['tmpls'])
            || !is_array($this->config['tmpls'])
            || !$this->config['tmpls']
        ) {
            throw new \Exception('Missing aliyun sms api template IDs.');
        }
    }

    protected function legalCfg($idx, $arr = null)
    {
        $arr = $arr ?? $this->config;
        return (
            isset($arr[$idx])
            && is_string($arr[$idx])
            && $arr[$idx]
        );
    }

    protected function execute($code = false)
    {
        $config = [
            'accessKeyId'       => $this->config['akid'],
            'accessKeySecret'   => $this->config['aksk'],
        ];

        $client  = $this->client($config);
        $sendSms = $this-> sendSms();
        $sendSms->setPhoneNumbers($this->mobile);
        $sendSms->setSignName($this->config['sign']);
        $sendSms->setTemplateCode($this->tmpl);

        if ($code) {
            $sendSms->setTemplateParam(['code' => $code]);
        }

        // $sendSms->setOutId('1');  // 短信流水号(可选)

        return $client->execute($sendSms);
    }

    protected function client($config)
    {
        return new Client($config);
    }

    protected function sendSms()
    {
        return new SendSms;
    }

    public function __send(
        $mobile,
        $mid,
        $mtype      = 'user',
        $tmplKey    = 'default',
        $checkcode  = false,
        $length     = 4,
        $verbose    = 1
    ) {
        if (! is_numeric($mid)
            || 1 > intval($mid)
            || !in_array(strtolower($mtype), ['user', 'staff', 'shop'])
        ) {
            throw new \Exception('Illegal member id or type');
        }
        // check tmpl key legal or not
        if (! $this->legalCfg($tmplKey, $this->config['tmpls'])) {
            throw new \Exception(
                'Can not find template key `'
                .$tmplKey
                .'` in current template IDs configuration.'
            );
        }
        // check mobile format
        if (! preg_match('/^1[34578]\d{9}$/u', $mobile)) {
            throw new \Exception(
                'Illegal mobile number format.'
            );
        }

        $this->tmpl   = $this->config['tmpls'][$tmplKey];
        $this->mobile = $mobile;

        $err = 503;
        $msg = Tool::sysMsg('SERVICE_UNAVAILABLE');
        $updateOrStoreSuccess = true;

        if ('group_send' != mb_substr($tmplKey, 0, 10)
            && (false === $checkcode)
        ) {
            $checkcode = $this->randcode($length, $verbose);
            // check if has stored before
            $storedBefore = \DB::table('checkcode')
            ->whereKeyAndMidAndMtypeAndMobile($tmplKey, $mid, $mtype, $mobile)
            ->get()
            ->toArray();

            if ($storedBefore) {
                // update checkcode in table
                $updateOrStoreSuccess = \DB::table('checkcode')
                ->whereKeyAndMidAndMtypeAndMobile($tmplKey, $mid, $mtype, $mobile)
                ->update([
                    'value'     => $checkcode,
                    'create_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                // store checkcode in table
                $updateOrStoreSuccess = \DB::table('checkcode')
                ->insertGetId([
                    'key'    => $tmplKey,
                    'value'  => $checkcode,
                    'mid'    => $mid,
                    'mtype'  => $mtype,
                    'mobile' => $mobile,
                    'create_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // send sms
        if ($updateOrStoreSuccess) {
            $res = $this->execute($checkcode);
            $sendSuccess = ('OK' == $res->Code);
            if ($sendSuccess) {
                $err  =  0;
                $msg  =  'ok';
            } else {
                $msg  =  $this->errMsgMap[$res->Code] ?? '系统错误, 发送失败';
            }
            $code = $res->Code;
        }

        return [
            'err'  => $err,
            'msg'  => $msg,
            'code' => $code,
        ];
    }

    public function send(Request $req)
    {
        $this->validate($req, [
            'mobile'  => 'required|integer',
            'mid'     => 'required|integer|min:1',
            'mtype'   => 'required|in:user,staff,shop',
        ]);

        $tmpl    = $req->get('tmpl') ?? 'default';
        $length  = intval($req->get('length') ?? 4);
        $verbose = intval($req->get('verbose') ?? 1);
        $code    = $req->get('code') ?? false;

        if ($length < 4) {
            return response()->json([
                'err' => 400,
                'msg' => Tool::sysMsg('CHECKCODE_LENGTH_LESS_THAN_4'),
            ], 400);
        }

        if ($verbose <= 0) {
            return response()->json([
                'err' => 400,
                'msg' => Tool::sysMsg('CHECKCODE_VERBOSE_NUMBER_ILLEGAL'),
            ], 400);
        }

        return $this->__send(
            $req->get('mobile'),
            $req->get('mid'),
            $req->get('mtype'),
            $tmpl,
            $code,
            $length,
            $verbose
        );
    }

    // $type = 1 => pure number
    // $type = 2 => number with chars
    protected function randCode($length = 4, $type = 1): string
    {
        if (!is_integer($length) || $length < 0) {
            throw new \Exception(
              'Checkcode length must be an integer over 0.'
            );
        }

        $chars = $pureNum = str_split('0123456789');

        if (2 == $type) {
            $charLower = 'abcdefghijklmnopqrstuvwxyz';
            $charUpper = strtoupper($charLower);
            $chars     = array_merge(
                $chars,
                str_split($charLower.$charUpper)
            );
        }

        $charsLen = count($chars) - 1;

        $code = '';
        for ($i=0; $i < $length; ++$i) {
            $code .= $chars[mt_rand(0, $charsLen)];
        }

        return $code;
    }

    public $errMsgMap = [
        'OK'                              => '请求成功',
        'isp.RAM_PERMISSION_DENY'         => 'RAM权限DENY',
        'isv.OUT_OF_SERVICE'              => '业务停机',
        'isv.PRODUCT_UN_SUBSCRIPT'        => '未开通云通信产品的阿里云客户',
        'isv.PRODUCT_UNSUBSCRIBE'         => '产品未开通',
        'isv.ACCOUNT_NOT_EXISTS'          => '账户不存在',
        'isv.ACCOUNT_ABNORMAL'            => '账户异常',
        'isv.SMS_TEMPLATE_ILLEGAL'        => '短信模板不合法',
        'isv.SMS_SIGNATURE_ILLEGAL'       => '短信签名不合法',
        'isv.INVALID_PARAMETERS'          => '参数异常',
        'isp.SYSTEM_ERROR'                => '系统错误',
        'isv.MOBILE_NUMBER_ILLEGAL'       => '非法手机号',
        'isv.MOBILE_COUNT_OVER_LIMIT'     => '手机号码数量超过限制',
        'isv.TEMPLATE_MISSING_PARAMETERS' => '模板缺少变量',
        'isv.BUSINESS_LIMIT_CONTROL'      => '业务限流',
        'isv.INVALID_JSON_PARAM'          => 'JSON参数不合法，只接受字符串值',
        'isv.BLACK_KEY_CONTROL_LIMIT'     => '黑名单管控',
        'isv.PARAM_LENGTH_LIMIT'          => '参数超出长度限制',
        'isv.PARAM_NOT_SUPPORT_URL'       => '不支持URL',
        'isv.AMOUNT_NOT_ENOUGH'           => '账户余额不足',
    ];
}


======================Waimai/sms/aliyun.mdl.php================================
<?php

class Mdl_Sms_Aliyun extends Model
{
    private $tmpls = [
        'default' => '默认短信验证码模版',
        'user_signup' => '用户注册短信验证码模版',
        'reset_secure_passwd' => '用户充值安全密码短信验证码模版',
        'group_send_ylh' => '您已成功注册云联惠账号，云联惠会员激活链接： http://uc.yunlianhui.cn/index.php/Activate/index.html',
    ];

    protected function hasOrigin()
    {
        if (isset($_REQUEST['origin'])) {
            return $_REQUEST['origin'];
        } elseif ($vars = file_get_contents(('php://input'))) {
            if ($arr = json_decode($vars, true)
                && isset($arr['origin'])
                && is_string($arr['origin'])
                && $arr['origin']
            ) {
                return $arr['origin'];
            }
        }

        return false;
    }

    protected function hasSSID()
    {
        $ssid = null;

        if (isset($_REQUEST['__ssid']) && is_string($_REQUEST['__ssid'])) {
            $ssid = $_REQUEST['__ssid'];
        } elseif ($vars = file_get_contents(('php://input'))) {
            if ($arr = json_decode($vars, true)
                && isset($arr['__ssid'])
                && is_string($arr['__ssid'])
                && $arr['__ssid']
            ) {
                $ssid = $arr['__ssid'];
            }
        }

        return $ssid;
    }

    // Aliyun send
    public function send($params)
    {
        if (! isset($params['img_code'])) {
            return $this->msgbox->add('请输入图形验证码', 4001)->response();
        } elseif (! isset($params['mobile'])) {
            return $this->msgbox->add('请输入手机号', 4002)->response();
        }

        if (($origin = $this->hasOrigin()) && ('app' == $origin)) {
            $img_code = K::M('pdo/pdo')->fetch('
                SELECT `value`, `create_at`
                FROM `checkcode`
                WHERE `key` = "img_code"
                AND `mtype` = "img_code"
                AND `mobile` = "'.$params['mobile'].'"
            ');

            if (! $img_code
                || ! isset($img_code['value'])
                || (
                    strtoupper($img_code['value'])
                    != strtoupper($params['img_code'])
                )
            ) {
                return $this->msgbox->add('图形验证码错误', 4003)->response();
            } elseif (! isset($img_code['create_at'])
                     || (time() - strtotime($img_code['create_at']) > 300)
            ){
                return $this->msgbox->add('图形验证码已过期', 4004)->response();
            }
        } else {
            if (! K::M('magic/verify')->check(
                $params['img_code'],
                $this->hasSSID()
            )) {
                return $this->msgbox->add('图形验证码错误', 4005)->response();
            }
        }

        $content  = '使用';
        $content .= isset($params['tmpl']) ? (
            isset($this->tmpls[$params['tmpl']])
            ? $this->tmpls[$params['tmpl']]
            : '未知短信模板'
        ) : $this->tmpls['default'];

        if (isset($params['code']) && is_string($params['code'])) {
            $content .= '发送的验证码为：'.$params['code'];
            $createAt = date('Y-m-d H:i:s');
            $values = "sms_code_waimai, '{$params['code']}', sms_code_waimai, '{$params['mobile']}', '{$createAt}'";
        }

        $sqlDelete = '
          DELETE FROM `checkcode`
          WHERE `key` = "sms_code_waimai"
          AND `mtype` = "sms_code_waimai"
          AND `mobile` = "'.$mobile.'"
        ';

        $sqlCreate = '
            INSERT INTO `checkcode`
            (`key`, `value`, `mtype`, `mobile`, `create_at`)
            VALUES ('.$values.')
        ';

        $resDelete = K::M('pdo/pdo')->query(sqlDelete);
        $resCreate = K::M('pdo/pdo')->query(sqlCreate);

        if (! (resDelete && resCreate)) {
            echo json_encode([
                'error'     => 501,
                'message'   => '数据更新异常',
            ]);
            exit;
        }
    }

    $params = is_array($params) ? json_encode($params) : (string)$params;

    $ret = K::M('api/hcm')->launch(
        '/aliyun/sms/send',
        'POST',
        [],
        $params
    );

    $data = [
        'mobile'  => $mobile,
        'content' => $content,
        'sms'     => 'aliyun',
        'clientip'=> __IP,
        'dateline'=> time(),
    ];

    if (isset($ret['res']['err']) && (0 == $ret['res']['err'])) {
        $data['status'] = 1;
    } else {
        $data['status'] = 0;
        return $this->msgbox->add('短信发送失败，请稍后再试', 4001)->response();
    }

    // Create sms log
    K::M('sms/log')->create($data);

    return (1 == $data['status']) ? true : false;
}