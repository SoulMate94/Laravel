<?php

require_once __DIR__.'/../vendor/autoload.php';

Dotenv::load(__DIR__.'/../');

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades();

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->configure('entrust');
$app->configure('oauth2');
//$app->configure('auth');//加载用户认证配置文件
$app->configure('alipay');//加载支付宝支付配置文件


//采用redis原生连接方式,需要原生库支持，http://www.cnblogs.com/zhaobolu/p/3721823.html

$app->singleton('redis', function(){
    $redis = new Redis;
    $redis->pconnect(env('REDIS_HOST'),env('REDIS_PORT'));
    return $redis;
});
unset($app->availableBindings['redis']);
/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/
$app->middleware([

]);

$app->routeMiddleware([
    'csrf' => \App\Http\Middleware\CsrfTokenMiddleware::class,
    'oauth' => \App\Http\Middleware\OAuthMiddleware::class,
    'csrfAndOauth'=>\App\Http\Middleware\OAuthAndCsrfTokenMiddleware::class
]);
// $app->middleware([
//     // Illuminate\Cookie\Middleware\EncryptCookies::class,
//     // Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
//     // Illuminate\Session\Middleware\StartSession::class,
//     // Illuminate\View\Middleware\ShareErrorsFromSession::class,
//     // Laravel\Lumen\Http\Middleware\VerifyCsrfToken::class,
// ]);

// $app->routeMiddleware([

// ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

$app->register(Zizaco\Entrust\EntrustServiceProvider::class);//注册用户权限门面
$app->register(Illuminate\Redis\RedisServiceProvider::class);//注册Redis缓存门面
$app->register(Overtrue\LaravelWechat\ServiceProvider::class);//注册微信SDK封装包门面
$app->register(App\Providers\Payment\PaymentProvider::class);//注册支付封装包门面
$app->register(\LucaDegasperi\OAuth2Server\Storage\FluentStorageServiceProvider::class);
$app->register(\LucaDegasperi\OAuth2Server\OAuth2ServerServiceProvider::class);

$app->register(App\Providers\Common\CommonProvider::class);//注册通用部分门面
$app->register(App\Providers\Utils\ResultProvider::class);//注册通用部分门面
/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../app/Http/routes.php';
});

return $app;
