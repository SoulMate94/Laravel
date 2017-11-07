<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Environment 应用 环境
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | 这些数值决定了 当前 环境，你的应用的 当前运行的环境。
    | running in. This may determine how you prefer to configure various
    | 他们也许还决定了 如何 优化配置你的 多样性的 服务器 你的 应用单元 可以应用他
    | services your application utilizes. Set this in your ".env" file.
    | 设置这些配置文件 在 .env 文件里面
    |
    */

    'env' => env('APP_ENV', 'production'),// 哥你这个意思是说 生产环境是吧  都注册成为系统

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode 应用调试模式
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | 当你的应用 处于在 调试模式， 详细的错误新
    | stack traces will be shown on every error that occurs within your
    | 记录运行轨迹 将展示 每一个错误 都 发生在你的应用里面，如果 不行的话，
    | application. If disabled, a simple generic error page is shown.
    | 一个简单的生成的错误页面会显示
    */

    'debug' => env('APP_DEBUG', false), // 默认的 配置方式， false

    /*
    |--------------------------------------------------------------------------
    | Application URL  应用 URL 地址
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | 这里的 URL 是用于控制来适当的生产 URLs 当用于 更加艺术的模式生产工具。
    | the Artisan command line tool. You should set this to the root of
    | your application so that it is used when running Artisan tasks.
    | 你应设置这里的 根目录 这样可以更好的 运行 匠人艺术。
    */

    'url' => 'http://localhost',

    /*
    |--------------------------------------------------------------------------
    | Application Timezone  应用时区
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | 此处 有可能特别 默认的时区 ，对于你应用， 这里会影响到 date 跟 date-time 函数
    | will be used by the PHP date and date-time functions. We have gone
    | ahead and set this to a sensible default for you out of the box.
    | 我们有 顺路 设置了一个 明智的 默认 对于你调制出来这里的 box
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration 应用 本地 设置
    |--------------------------------------------------------------------------
    | 应用本地目录 默认的 配置 将用户 转换 到 服务器证明，你也可以更自由的设置 数值
    | The application locale determines the default locale that will be used
    | by the translation service provider. You are free to set this value
    | to any of the locales which will be supported by the application.
    | 任何的 位置 可以 支持以应用，说白了就是 语言本地化。
    */

    'locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Application Fallback Locale  应用 本地化的一个反馈
    |--------------------------------------------------------------------------
    |
    | The fallback locale determines the locale to use when the current one
    | is not available. You may change the value to correspond to any of
    | the language folders that are provided through your application.
    | 返回的本地化目录 ，当你的 当前的一个不能使用的，你 可能需要改变 适配的 任何语言目录
    | 为了更好的 加固你的程序。
    */

    'fallback_locale' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Encryption Key 加密 key
    |--------------------------------------------------------------------------
    |
    | This key is used by the Illuminate encrypter service and should be set
    | to a random, 32 character string, otherwise these encrypted strings
    | will not be safe. Please do this before deploying an application!
    | 这 key 使用用 光明加密服务，应该设置成为 随机的 32 为字符串， 否则的话，加密的将不安全，
    | 请提前设置这个加密
    */

    'key' => env('APP_KEY'),// 获取到 对应的 环境配置， 可以两层 配置的方式， 老外的思路还是很清晰的

    'cipher' => 'AES-256-CBC', // 加密密码

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration  日志记录 配置
    |--------------------------------------------------------------------------
    | 此处 你 可能配置 日志设置你的应用
    | Here you may configure the log settings for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | 盒子外侧， laravel 中应用的 是Monolog PHP 日志库，
    | you a variety of powerful log handlers / formatters to utilize.
    | 你 多样化的 强大的 日志处理 来使用
    | Available Settings: "single", "daily", "syslog", "errorlog"
    | 可用 设置  单一 日志 系统 错误 日志
    */

    'log' => env('APP_LOG', 'single'), // 日志 记录 单一记录模式

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers 自动加载系统 提供者
    |--------------------------------------------------------------------------
    | 这里的 服务提供 者 目录 将会自动加载 应用中的 应该
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    | 免费 可以 加载  增加你的 服务器 this 数组 和 扩展 函数应用
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         * 框架支撑
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,

        /*
         * Application Service Providers...
         * 框架服务支撑
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases  类别名 话  看来这个哥们 是习惯了 黑洞洞的界面了，哈哈
    |--------------------------------------------------------------------------
    | 这里的数组 代表了 类的别名 将注册 到应用开始
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    | 这个 可以 用 “慵懒” 的方式进行加载
    */

    'aliases' => [

        'App'       => Illuminate\Support\Facades\App::class,
        'Artisan'   => Illuminate\Support\Facades\Artisan::class,
        'Auth'      => Illuminate\Support\Facades\Auth::class,
        'Blade'     => Illuminate\Support\Facades\Blade::class,
        'Cache'     => Illuminate\Support\Facades\Cache::class,
        'Config'    => Illuminate\Support\Facades\Config::class,
        'Cookie'    => Illuminate\Support\Facades\Cookie::class,
        'Crypt'     => Illuminate\Support\Facades\Crypt::class,
        'DB'        => Illuminate\Support\Facades\DB::class,
        'Eloquent'  => Illuminate\Database\Eloquent\Model::class,
        'Event'     => Illuminate\Support\Facades\Event::class,
        'File'      => Illuminate\Support\Facades\File::class,
        'Gate'      => Illuminate\Support\Facades\Gate::class,
        'Hash'      => Illuminate\Support\Facades\Hash::class,
        'Lang'      => Illuminate\Support\Facades\Lang::class,
        'Log'       => Illuminate\Support\Facades\Log::class,
        'Mail'      => Illuminate\Support\Facades\Mail::class,
        'Password'  => Illuminate\Support\Facades\Password::class,
        'Queue'     => Illuminate\Support\Facades\Queue::class,
        'Redirect'  => Illuminate\Support\Facades\Redirect::class,
        'Redis'     => Illuminate\Support\Facades\Redis::class,
        'Request'   => Illuminate\Support\Facades\Request::class,
        'Response'  => Illuminate\Support\Facades\Response::class,
        'Route'     => Illuminate\Support\Facades\Route::class,
        'Schema'    => Illuminate\Support\Facades\Schema::class,
        'Session'   => Illuminate\Support\Facades\Session::class,
        'Storage'   => Illuminate\Support\Facades\Storage::class,
        'URL'       => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View'      => Illuminate\Support\Facades\View::class,

    ],

];