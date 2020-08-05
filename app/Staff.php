<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
    
    public function scopeActiveStaff($query){
        return $query->where([['status', '=', "Active"]])->get();
    }
    
}
