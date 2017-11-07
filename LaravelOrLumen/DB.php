<?php

=======================CURD====================================================
$insert_id = DB::table('table_name')
                ->insertGetId([
                'id' => $id,
                ...
             ]);

$affect_rows = DB::table('table_name')
                  ->where(['id' => $id])
                  ->limit(1)
                  ->delete();

$data = DB::table('table_name')
            ->where(['id' => $id])
            ->update(['name' => $name]);

$data = DB::table('table_name')
            ->select('id, name')
            ->where(['dateline' => $dateline])
            ->paginate(20);

===标准模型
namespace App\Models;

use Illuminate\Support\Facades\DB;

class Admin extend Model
{
    protected $table        = 'admin';
    protected $primaryKey   = 'admin_id';
    public $timestamps      = false;
}