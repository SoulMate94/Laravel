<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
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
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'api' => [
            'driver' => 'token',
            // 'driver' => 'jwt',  // by Later.C
            'provider' => 'users',
        ],
        /**
         * 这里是我自定义的guard,这里我叫staffs，你也可以根据自己的业务需求设置admins等，并且我
         * 需要实现json web token认证
         */
        'staffs' => [
            'driver'    =>  'jwt',  // 结合扩展这里定义即生效
            'provider'  =>  'staffs'
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
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
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\User::class,  // 这里注意修改命名空间 通常是'model' => App\Models\User::class,
        ],

        /**
         * 同样的这里定义自己的provider
         */
        'staffs'  =>  [
            'driver'  =>  'database',
            'model'   =>  App\Models\Staff::class,
        ]

        // 'users' => [
        //     'driver' => 'database',
        //     'table' => 'users',
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
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
    */

    'passwords' => [
        'users' => [
            'provider'  => 'users',
            'email'     =>  'auth.emails.password',
            'table'     => 'password_resets',
            'expire'    => 60,
        ],

        /**
         * 这里我并没有设置如下，因为我的staff表并没有email字段，默认的重置密码功能暂时没考虑
         */
        /*'staffs' => [
            'provider' => 'staffs',
            'email' => 'auth.emails.password',
            'table' => 'password_resets',
            'expire' => 60,
            ],*/
    ],

];
