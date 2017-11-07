<?php

============================WAIMAIO2O==========================================
    private function requestData($url='', $type='get', $data=[])
    {
        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if ($type == 'post') {
            // post数据
            curl_setopt($ch, CURLOPT_POST, 1);
            // post的变量
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // 执行并获取HTML的文档内容
        $outopt = curl_exec($ch);
        // 检测是否有报错
        if (curl_errno($ch)) {
            die('Curl error: ' . curl_error($ch));
        }
        //释放curl句柄
        curl_close($ch);
        //打印获得的数据
        return $output;
    }

    public function getJWTString($params = [])
    {
        $header = base64_encode(json_encode([
            'typ' => 'JWT',
            'alg' => 'SHA256',
        ]));
        $claims = [
            'exp' => _TIME+604800,
            'nbf' => __TIME,
            'iat' => __TIME,
        ];
        $payload = base64_decode(json_encode(array_merge($params, $claims)));
        $signature = base64_encode(hash_hmac('sha256', $header.'.'.$payload, __CFG::JWT_SECRET_KEY));

        return implode('.', [$header, $payload, $signature]);
    }

    // re-packing from kuaiqian demo for mobile
    public function kqPayCallbackSignVerify($params, $cert)
    {
        // by rmb_demo_php @kuaiqian
        function kq_ck_null($kq_va, $kq_na) {
            if ($kq_va == "") {
                return $kq_va="";
            } else {
                return $kq_va=$kq_na.'='.$kq_va.'&';
            }
        }
        //人民币网关账号，该账号为11位人民币网关商户编号+01,该值与提交时相同。
        $kq_check_all_para=kq_ck_null($params['merchantAcctId'], 'merchantAcctId');
        //网关版本，固定值：v2.0,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($params['version'], 'version');
        //语言种类，1代表中文显示，2代表英文显示。默认为1,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($params['language'], 'language');
        //签名类型,该值为4，代表PKI加密方式,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($params['signType'], 'signType');
        //支付方式，一般为00，代表所有的支付方式。如果是银行直连商户，该值为10,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($params['payType'], 'payType');
        //银行代码，如果payType为00，该值为空；如果payType为10,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($params['bankId'], 'bankId');
        //商户订单号，,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($params['orderId'], 'orderId');
        //订单提交时间，格式：yyyyMMddHHmmss，如：20071117020101,该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($params['orderTime'], 'orderTime');
        //订单金额，金额以“分”为单位，商户测试以1分测试即可，切勿以大金额测试,该值与支付时相同。
        $kq_check_all_para.=kq_ck_null($params['orderAmount'], 'orderAmount');
        $kq_check_all_para.=kq_ck_null($params['bindCard'], 'bindCard');
        $kq_check_all_para.=kq_ck_null($params['bindMobile'], 'bindMobile');
        // 快钱交易号，商户每一笔交易都会在快钱生成一个交易号。
        $kq_check_all_para.=kq_ck_null($params['dealId'], 'dealId');
        //银行交易号 ，快钱交易在银行支付时对应的交易号，如果不是通过银行卡支付，则为空
        $kq_check_all_para.=kq_ck_null($params['bankDealId'], 'bankDealId');
        //快钱交易时间，快钱对交易进行处理的时间,格式：yyyyMMddHHmmss，如：20071117020101
        $kq_check_all_para.=kq_ck_null($params['dealTime'], 'dealTime');
        //商户实际支付金额 以分为单位。比方10元，提交时金额应为1000。该金额代表商户快钱账户最终收到的金额。
        $kq_check_all_para.=kq_ck_null($params['payAmount'], 'payAmount');
        //费用，快钱收取商户的手续费，单位为分。
        $kq_check_all_para.=kq_ck_null($params['fee'], 'fee');
        //扩展字段1，该值与提交时相同
        $kq_check_all_para.=kq_ck_null($params['ext1'], 'ext1');
        //扩展字段2，该值与提交时相同。
        $kq_check_all_para.=kq_ck_null($params['ext2'], 'ext2');
        //处理结果， 10支付成功，11 支付失败，00订单申请成功，01 订单申请失败
        $kq_check_all_para.=kq_ck_null($params['payResult'], 'payResult');
        //错误代码 ，请参照《人民币网关接口文档》最后部分的详细解释。
        $kq_check_all_para.=kq_ck_null($params['errCode'], 'errCode');

        $trans_body = mb_substr($kq_check_all_para, 0, mb_strlen($kq_check_all_para)-1);
        $MAC = base64_decode($params['signMsg']);

//        $fp = fopen("./99bill[1].cert.rsa.20140803.cer", "r");
//        $cert = fread($fp, 8192);
//        fclose($fp);

        $pubkeyid = openssl_get_publickey($cert);

        return openssl_verify($trans_body, $MAC, $pubkeyid);
    }

    // 生成一个二维码图片
    public function generateQRCodeImage(
        $content,
        $errorLevel = 'L',
        $pointSize = 10,
        $margin = 1
    ){
        if (! class_exists('QRcode')) {
            include_once __CORE_DIR.'libs/qrcode/phpqrcode.php';
        }

        QRcode::png($content, false, $errorLevel, $pointSize, $margin);

        exit;
    }

    public function postJsonApiByCurl($uri, $header, $paramStr)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL             => $uri,
            CURLOPT_HTTPHEADER      => $headers,
            CURLOPT_POST            => true,
            CURLOPT_POSTFIELDS      => $paramStr,
            CURLOPT_RETURNTRANSFER  => true,
        ]);
        $res = curl_exec($ch);

        $errNo  =  curl_errno($ch);
        $errMsg =  curl_error($ch);

        curl_close($ch);

        return [
            'errNo'  => $errNo,
            'errMsg' => $errMsg,
            'res'    => json_decode($res, true),
        ];
    }

    public function requestJsonApi($uri, $type  = 'POST', $params = [])
    {
        $ch = curl_init();

        $setOpt = [
            CURLOPT_URL        => $uri,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; Charset: UTF-8',
            ],
            CURLOPT_RETURNTRANSFER => true,
        ];

        if ('POST' == $type) {
            $setOpt = array_merge($setOpt, [
                CURLOPT_POST        => true,
                CURLOPT_POSTFIELDS  => $params,
            ]);
        }

        curl_setopt_array(ch, $setOpt);

        $res = curl_exec($ch);

        $errNo  = curl_errno($ch);
        $errMsg = curl_error($ch);

        curl_close($ch);

        return [
            'err' => $errNo,
            'msg' => $errMsg,
            'res' => json_decode($res, true),
        ];
    }

    public function isTimestamo($timestamp)
    {
        return (
            is_integer($timestamp)
            && ($timestamp >= 0)
            && ($timestamp <= 2147472000)
        );
    }

    public function jsonResponse($data)
    {
        if (! headers_sent()) {
            header('Content-Type: application/json; charset=UTF-8');
        }

        echo json_encode(
            $data,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );

        exit;
    }

    public function log(
        $msg,
        $file   = __FILE__,
        $line   = __LINE__,
        $name   = 'hcm_tool_log'
        $append = true
    ) {
        $path     =  __CORE_DIR.'data/logs/';
        $logFile  =  $path.$name.'.php';
        $flag     =  $append ? FILE_APPEND : LOCK_EX;
        $data     = <<<'STR'
<?php exit('Access denied');?>
STR;
        $data    .=  PHP_EOL.date('Y-m-d H:i:s').PHP_EOL
        .'=>'.$file
        .'#'.$line.PHP_EOL
        .'=>'.$msg
        .PHP_EOL.PHP_EOL;

        file_put_contents($logFile, $data, $flag);

        return $this;
    }

    //二维数组按照字段排序
    public function ArrSortByField(
        $arrays,
        $sort_key,
        $sort_order = SORT_ASC,
        $sort_type  = SORT_NUMERIC
    ) {
        if (is_array($arrays)) {
            foreach ($arrays as $key => $value) {
                if (is_array($array)) {
                    if (empty($array['coords'])) {
                        unset($arrays[$key]);
                    } else {
                        $key_arrays[] = $array[$sort_key];
                    }
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
        return $arrays;
    }

===api/agent.mdl.php
class APUExcp extends \Exception
{
    public function __construct($msg)
    {
        K::M('tools/tool')->log(
            $msg.PHP_EOL
            .'=> Exception Trace:'.json_encode($this->getTrace()),
            $this->getFile(),
            $this->getLine()
        );

        exit;
    }
}

class Mdl_Api_Agent
{
    protected $apiEnv    = null;
    protected $apiPrefix = 'non-agent';

    public function __construct($system, $apiEnv = null)
    {
        $apiEnv = ($apiEnv ? :(
            (
                defined('__CFG::HCM_API_ENV')
                && is_string(__CFG::HCM_API_ENV)
                && __CFG::HCM_API_ENV
            ) ? __CFG::HCM_API_ENV : false
        ));
        if (! $apiEnv) {
            throw new APIExcp("Missing api env.");
        }

        $ak = (
            defined('__CFG::HCM_API_AK')
            && is_string(__CFG::HCM_API_AK)
            && __CFG::HCM_API_AK
        ) ? __CFG::HCM_API_AK : false;
        if (!$ak) {
            throw new APIExcp('Missing access token.');
        }

        $uriArr = parse_url($apiEnv);
        if (! isset($uriArr['host']) || !is_string($uriArr['host'])) {
            throw new APIExcp('Missing or illegal api host.');
        }

        $scheme = isset($uriArr['scheme'])
        ? $uriArr['scheme'].'://' : 'http://';

        $this->apiEnv = $scheme.$uriArr['host'];
        $this->ak = $ak;
    }

    public function buildUri($api)
    {
        $api = $this->apiPrefix.'/'.$api;
        $api = implode('/', array_filter(explode('/', $api)));

        return $this->apiEnv.'/'.$api;
    }

    protected function getSecretToken()
    {
        $sk = (
            defined('__CFG::HCM_API_SK')
            && is_string(__CFG::HCM_API_SK)
            && __CFG::HCM_API_SK
        ) ? __CFG::HCM_API_SK : false;

        if (! $sk) {
            throw new APIExcp('Missing secret token.');
        }

        return $sk;
    }

    public function launch(
        $api,
        $type    = 'GET',
        $headers = [],
        $params  = [],
    ) {
        $ch = curl_init();

        $apiType  = ('GET' === strtoupper($type)) ? 'READ' : 'WRITE';
        $authKeys = ('WRITE' === $apiType)
        ? ['HCM-API-SK:'.$this->getSecretToken()]
        : [];

        $_header = [
            'Content-Type: application/json; Charset: UTF-8',
            'HCM-API-TYPE: '.$apiType,
            'HCM-API-AK: '.$this->ak,
        ];

        if (is_array($headers) && $headers) {
            $_headers = array_merge($_headers, $headers, $sk);
        }

        $setOpt = [
            CURLOPT_URL         => $this->buildUri($api),
            CURLOPT_HTTPHEADER  => $_headers,
            CURLOPT_RETURNTRANSFER  => true,
        ];

        if ('POST' == type) {
            $setOpt = array_merge($setOpt, [
                CURLOPT_POST        =>  true,
                CURLOPT_POSTFIELDS  => $params,
            ]);
        }

        curl_setopt_array($ch, $setOpt);

        $res    = curl_exec($ch);
        $errNo  = curl_errno($ch);
        $errMsg = curl_error($ch);

        curl_close($ch);

        K::M('tools/tool')->log(
            'The result of calling json api `'.$uri.'`: '
            .$res,
            __FILE__,
            __LINE__,
            'api_call'
        );

        return [
            'err' => $errNo,
            'msg' => $errMsg,
            'res' => json_decode($res, true),
        ];
    }
}


===content/censor.mdl.php
class Mdl_Content_Censor extends Mdl_Table
{
    public $succeed = true;
    public $code    = 300;  //{300:成功,301:含屏蔽词,302:敏感词}
    public $message = '';   //{返回以","分隔的词}
    public $_censor = null;

    public function __construct(&$system)
    {
        parent::__construct($system);
        $this->_censor = K::M('data/censor')->fetch_all();
    }

    // 敏感词
    public function censor($content)
    {
        if (! empty($this->_censor['censor'])) {
            foreach ((array)$this->_censor['censor'] as $censor) {
                // 用了提效率用preg_match 替换 preg_match_all
                if (preg_match($censor, $content, $match)) {
                    $this->succeed  = false;
                    $this->code     = 302;
                    $this->message  = $match[0];
                    return false;
                }
            }
        }
        $this->succeed = true;
        $this->code    = 300;
        return $this->succeed;
    }

    // 屏蔽词
    public function shild($content)
    {
        if (!empty($this->_censor['shilde'])) {
            foreach ((array)$this->_censor['shilde'] as $shild) {
                //用了提效率用preg_match 替换 preg_match_all
                if(preg_match($shild,$content,$match)){
                    $this->succeed  = false;
                    $this->code     = 301;
                    $this->message  = $match[0];
                    return false;
                }
            }
        }
        $this->succeed = true;
        $this->code    = 300;
        return $this->succeed;
    }

    // 过滤词
    public function filter($content)
    {
        if (!empty($this->_censor['filter'])) {
            // 注意这里存在多次替换
            foreach ((array)$this->_censor as $filter) {
                $content = preg_match($filter['find'], $filter['replace'], $content);
            }
        }
        return $content;
    }
}

===content/html.mdl.php
class Mdl_Content_Html
{
    public function filter($content)
    {
        //屏蔽的标签
        $content = preg_replace("/<[\/]{0,1}(iframe|script|style|form|link|meta|ifr|fra|input|textarea|button)[^>]*>/is", '', $content);
        //屏蔽on事件
        $content = preg_replace("/<([^>]+)(href|src|background|url|dynsrc|expression|codebase)\s*[=:(]([ \"']{0,1}(javascript|vbscript):[^>]+[ \"']{0,1})([^>]+)>/is","<\\1\\5>",$content);
        //屏蔽 href=javascript:
        $content = preg_replace("/<([^>]+?)(on[a-z]+?=[^>]+?)>/ies","\$this->__filter_onevent('<\\1\\2>')",$content);
        //去掉样式表class
        //$content = preg_replace("/<([^>]+?)class\s*=([\"']{0,1}[^>]+?[\"']{0,1})(.*)>/is","<\\1\\3>",$content);
        //div转换成p
        $content = preg_replace("/<([\/]{0,1})div(\s+[^>]*)?>/is", "<\\1p>", $content);
        return $content;
    }

    public function __filter_onevent($content)
    {
        $content = preg_replace("/\\\\/",'',$content);
        $content = preg_replace("/on[a-z]+=\".+?\"/is",'',$content);
        $content = preg_replace('/on[a-z]+=\'.+?\'/is','',$content);
        $content = preg_replace('/on[a-z]+=[^\s]+/','',$content);
        return $content;
    }

    /**
     * 过滤掉html标记,得到纯文本
     * $content 待处理里内容
     * $space   是否过滤掉多余的空白{\n,\r,空格等}
     */
    public function text($content, $space=false)
    {
        $content = preg_replace("/<style .*?<\/style>/is", "", $content);
        $content = preg_replace("/<iframe.*?<\/iframe>/is", "", $content);
        $content = preg_replace("/<script .*?<\/script>/is", "", $content);
        $content = preg_replace("/<meta(.*?)>/is", "", $content);
        $content = preg_replace("/<br \s*\/?\/>/i", "\n", $content);
        $content = preg_replace("/<\/?p>/i", "\n", $content);
        $content = preg_replace("/<\/?td>/i", "\n", $content);
        $content = preg_replace("/<\/?div>/i", "\n", $content);
        $content = preg_replace("/<\/?blockquote>/i", "\n", $content);
        $content = preg_replace("/<\/?li>/i", "\n", $content);
        $content = preg_replace("/\&nbsp\;/i", " ", $content);
        $content = html_entity_decode($content);
        $content = strip_tags($content);
        $content = preg_replace("/\&\#.*?\;/i", "", $content);
        if($space){
            $content = preg_replace('/(\s+)/s',' ',$content);
        }
        return $content;
    }

    public function encode($content)
    {
        if($content){
            //$content = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $content);
            $content = str_replace(array('&', '<', '>'), array('&amp;', '&lt;', '&gt;'), $content);
            if(strpos($content, '&amp;#') !== false) {
                $content = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $content);
            }
        }
        return $content;
    }

    public function decode($content)
    {
        if($content){
            //$content = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $content);
            $content = str_replace(array('&amp;', '&lt;', '&gt;'), array('&', '<', '>'), $content);
        }
        return $content;
    }
}


===getClientIP
function getClientIP()
{
    global $ip;
    if (getenv("HTTP_CLIENT_IP"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if(getenv("HTTP_X_FORWARDED_FOR"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if(getenv("REMOTE_ADDR"))
        $ip = getenv("REMOTE_ADDR");
    else $ip = "Unknow";
    return $ip;
}

public function  _Tree($data, $parent_id=0, $level=0, $isClear=true)
{
    static $ret = array();
    if ($isClear) {
        $ret = array();
        foreach ($data as $k => $v) {
            if ($v['pid'] == $parent_id) {
                $v['lv'] = $level;
                $ret[]   = $v;
                $this->_reSort($data, $v['id'], $level+1, false);
            }
        }

        return $ret;
    }
}

===CURL
    public function CURL($url,$data=null){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        if($data != null){
            curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 300); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $info = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            //echo 'Errno:'.curl_getinfo($curl);//捕抓异常
            //dump(curl_getinfo($curl));
        }
        return json_decode($info,true);
    }
=============================LUMEN=============================================
