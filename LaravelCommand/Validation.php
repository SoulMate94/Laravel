<?php

Validator::make(
    array('key' => 'Foo'),
    array('key' => 'required|in:Foo')
);

Validator::extend('foo', function($attribute, $value, $params){});

Validator::extend('foo', 'FooValidator@validate');

Validator::resolver(function($translator, $data, $rules, $msgs)
{
    return new FooValidator($translator, $data, $rules, $msgs);
});

