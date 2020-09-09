<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
    protected $guarded = ['id'];
    
    public function scopeActiveStaff($query){
        return $query->where([['status', '=', "Active"]])->get();
    }
    
    public function scopeActiveStaffOrderByInitial($query){
        return $query->where([['status', '=', "Active"]])->orderBy("initial")->get();
    }
    
}
