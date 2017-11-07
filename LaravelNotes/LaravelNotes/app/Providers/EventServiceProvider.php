<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     * 事件监听 关系图 对于应用
     * @var array
     */
    // 监听者
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any events for your application.
     * 注册了 事件启动
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
