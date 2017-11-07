<?php

Event::fire('foo.bar', array($bar));

// 注册一个事件监听器.
// void listen(string|array $events, mixed $listener, int $priority)

Event::listen('App\Events\UserSignup', function($bar){});
Event::listen('foo.*', function($bar){});
Event::listen('foo.bar', 'FooHandler', 10);
Event::listen('foo.bar', 'BarHandler', 5);

// 你可以直接在处理逻辑中返回 false 来停止一个事件的传播.
Event::listen('foor.bar', function($event){ return false; });
Event::subscribe('UserEventHandler');

