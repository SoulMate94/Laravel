<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults  认证的 默认设置
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    | 这里的选项 控制 这些 默认的  认证 “守卫” 并且密码 重置
    | 通过密码的形式重置 你的应用，你也可以改变那些 默认的 作为 需求的
    | 但是 他们 也是一个 开始 作为大部分 应用
    */

    'defaults' => [
        'guard' => 'web',  // web 就是 守卫
        'passwords' => 'users',  // 用户名 就是 密码
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards  认证守卫者
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | Of course, a great default configuration has been defined for you
    | here which uses session storage and the Eloquent user provider.
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | Supported: "session", "token"
    |
    | 下面，你也许 定义了 所有的 认证守卫 对于你的应用
    | 当然，一个 伟大的 不同配置 被定义了 对你来说
    | 被用于 session 存储  并且逼真的 用户提供
    | 所有的 认证 驱动 拥有一个 用户提供， 这里定义了 如何
    | 用户 去控制 你的数据库 跟 其它 存储 缓存 用于 控制 持久你的 用户 数据
    | 支持： session 跟 token 方式
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers  用户 驱动
    |--------------------------------------------------------------------------
    |
    | All authentication drivers have a user provider. This defines how the
    | users are actually retrieved out of your database or other storage
    | mechanisms used by this application to persist your user's data.
    |
    | If you have multiple user tables or models you may configure multiple
    | sources which represent each model / table. These sources may then
    | be assigned to any extra authentication guards you have defined.
    |
    | Supported: "database", "eloquent"
    |
    | 所有的权限认证驱动都包含一个用户支持，这里的 定义 了一个 如何 使用 实际 的 数据 存储 来持久化 数据
    | 如果你用于多用户表 或者 模式控制 多需要 对应的 模型下的表，这里的 源被 定义为 数据
    | 数据库 跟 逼真的
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,
        ],

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords  重置密码
    |--------------------------------------------------------------------------
    |
    | You may specify multiple password reset configurations if you have more
    | than one user table or model in the application and you want to have
    | separate password reset settings based on the specific user types.
    |
    | The expire time is the number of minutes that the reset token should be
    | considered valid. This security feature keeps tokens short-lived so
    | they have less time to be guessed. You may change this as needed.
    |
    | 这里 你可以 设置选用 充值密码 包含 预览 你密码重置的邮件，你也可以设置 表名 重置 tokens 对于你的应用
    | 你复合的修复密码方式 配置基本的用户模式
    | 这个 里面 超出时间 控制短连接，
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
        ],
    ],

];
