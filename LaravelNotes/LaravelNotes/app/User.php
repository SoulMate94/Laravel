<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;  // 权限表

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     * 这里是不可忽视的属性
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     * 这里是需要隐藏的属性
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    // 定义了 隐藏字段 + 必填字段
}
