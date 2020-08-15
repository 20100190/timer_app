<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleOrder extends Model
{
    protected $table = 'role_order';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
    
    public function scopeGetRoleData($query){
        return $query->orderBy("order","asc")->get();
    }
}
