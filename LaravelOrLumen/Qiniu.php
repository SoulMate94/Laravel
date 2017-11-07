<?php

===Controller

namespace App\Http\Controllers\ThirdParty;

use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;
use App\Models\{UploadScenario as US, UploadScenarioRouteMap as USAM};

class Qiniu extends Controller
{
    private $auth       = null;
    private $bucket     = null;
    private $jwt_auth   = null;
    private $req        = null;
    private $logPath    = null;

    public function __construct(Request $req)
    {
        $this->logPath  = storage_path().'/logs/';
        $this->req      = $req;
        $this->auth     = $req->get('qiniu_auth');
        $this->bucket   = $req->get('qiniu_bucket');
        $this->jwt_auth = $req->get('jwt_auth') ?? null;
    }

    public function getUploadScenarioIdByRoute($route)
    {
        $route  = parse_url($route, PHP_URL_PATH);
        $usrm   = USRM::where('route', $route)->first();

        return ($usrm && is_object($usrm) 
                && is_numeric($usrm->us_id)
                && $usrm->us_id>0
        ) ? $usrm->us_id : false;
    }

    public function uptokenOnly()
    {
        $token = $this->auth->uploadToken(
            $this->bucket,
            null,
            3600
        );

        return response()->json([
            'err' => 0,
            'msg' => 'ok',
            'dat' => [
                'token' => $token,
            ]
        ]);
    }

    public function getUploadToken()
    {
        $this->validate($this->req, [
            'id'    => 'required|integer|min:1',
            'route' => 'required',
        ]);

        $UploadScenarioId = $this->getUploadScenarioIdByRoute($this->req->route);
        if (false === $UploadScenarioId) {
            return reponse()->json([
                'error' =>'Upload Scenario Not Found.'
            ], 422);
        }

        $policy = [
            'callbackUrl' => route('qiniu_upload_callback'),
            'callbackBody'=> json_encode([
                // 'name'  => '$(fname)',
                // 'hash'  => '$(etag)',
                'fkey'  => '$(key)',
                'us_id' => $uploadScenarioId,
                'id'    => $this->req->id,
            ]),
        ];

        $upToken = $this->auth->uploadToken(
            $this->bucket,
            null,
            3600,
            $policy
        );

        return response()->json([
            'upToken' => $uoToken,
        ]);
    }

    public function uploadCallback()
    {
        $this->logCallbackRequest();

        $cbkBody  = file_get_contents('php://input');
        $cbkType  = 'application/x-www-from-urlencoded';
        $cbkAuth  = $_SERVER['HTTP_AUTHORIZATION'] ?? null;
        $cbkUrl   = route('qiniu_upload_callback');

        $cbkIsFromQiniu = $this->auth->verifyCallback(
            $cbkType,
            $cbkAuth,
            $cbkUrl,
            $cbkBody
        );

        $this->log('cbkIsFromQiniu.json', $cbkIsFromQiniu);

        if (! $cbkIsFromQiniu) {
            return response()->json([
                'ret' => 'failed',
            ]);
        }

        $updateRes = $this->updateUploadedKey($cbkBody) ? 'success' : 'failed';

        $this->log('updateRes.json', $updateRes, fasle);

        return response()->json(['ret' => $updateRes]);
    }

    /**
     * $param [String] $cbkBody [JSON]
     * $return [Mixed]
     */
    public function updateUploadedKey($cbkBoay)
    {
        if (!($cbkParams = json_decode($cbkBody, true)) ||
            !is_array($cbkParams) ||
            !isset($cbkParams['fkey']) ||
            !$cbkParams['fkey'] ||
            !isset($cbkParams['us_id']) ||
            intval($cbkParams['us_id']) < 0 ||
            !isset($cbkParams['id']) ||
            intval($cbkParams['id']) < 0
        ) {
            return false;
        }

        // get the upload scenario record
        $UploadScenario = US::find($cbkParams['us_id']);
        if (!$UploadScenario || !is_object($UploadScenario)) {
            return false;
        }

        // get primary key of the given table from upload scenario
        $relateTablePK = $UploadScenario->getRelateTablePK();

        if (false === $relateTablePK) {
            return false;
        }

        // update the uploaded key of relate table record
        return $upladScenario->updateUploadedKeyOfRelateTable(
            $relateTablePK,
            $cbkParams['id'],
            $cbkParams['fkey']
        );
    }

    public function log($logFil, $data, $isArr = true, $append = false)
    {
        if (in_array(env('APP_ENV'), [
            'production', 'prod', 'staging'
        ]) || env('APP_DEBUG') !== true) {
            return null;
        }

        $append = true ? FILE_APPEND : 0;
        $data   = $isArr ? json_encode($data) : $data;
        file_put_contents($this->logPath.$logFile, $data, $append);
    }

    public function logCallbackRequest()
    {
        $this->log('qiniu_cbk.post', $this->req->all());
        $this->log('qiniu_cbk.json', file_get_contents('php://input'), false);
        $this->log('qiniu_cbk.server', $_SERVER);
    }

}