<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader  注册 自动加载文件
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
| 依赖管理器提供了一保证,自动的产生类加载功能对于我们的应用，我们仅仅需要使用它就好了。
| 我们简单的请求在我们的脚本语言中，所以我们就不用担心人工加载任何我们的类了。
| 这个感觉，让我们觉得他美好跟放松。
*/

require __DIR__.'/../vendor/autoload.php';
// 包含了一个 自动加载文件
// 第一个：中断，开始。


/*
|--------------------------------------------------------------------------
| Turn On The Lights  打开引导的明灯
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
| 我们需要照亮的开发我们的php开发过程。所以让我们打开明灯。
| 这里的 bootstaps框架， 准备好了自己，让我们进行使用，然后她会自动加载完成应用。
| 所以我们可以运行他，让他回应浏览器，和反馈我们的用户。
*/

$app = require_once __DIR__.'/../bootstrap/app.php';
// 包含使用，进入。

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
| 一旦我们拥有了应用，我们能够处理增加的请求，通过内核，并且可以发送联合的反馈信息回到客户端
| 允许大家愉快的生成完美的应用，我们准备好了，为大家。
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
// 获取 类 执行文件

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
//  发送

$response->send();
// 发送 命令

$kernel->terminate($request, $response);
// 执行 请求数据 ，开始，结束
