<?php


======================GET
$app->get('dd','DataDict@index');
$app->get('/', function() use($app){
    return response()->json(['error' => '403'], 403);
});

======================POST
$app->post('login', 'Passport@loginAction');
$app->post('upload_cbk', [
    'as'   => 'qiniu_upload_callback',
    'uses' => 'Qiniu@uploadCallback',
]);

======================GROUP
$app->group([
    'middleware' => [
        'as_sk_auth',
    ],
], function() use($app){
    $app->post('aliyun/sms/send', 'AliyunSMS@send');
});

$app->group([
    'prefix'    =>  'user/{id}',
    'namespace' =>  'User',
    'middleware'=>  [
        'jwt_auth',
        'migrate_user_filter',
    ],
], function() use($app){
    $app->get('/', 'User@info');
    ...
});