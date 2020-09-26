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
    
    //承認権限 0: なし、1:あり
    public function scopeHaveApprovalAuthority($query,$email) {
        $isApprove = 0;        
        $staffData = $query->where("email","=",$email)->get();
        foreach($staffData as $item){
            $isApprove = $item->permission_approve;            
        }
        
        return $isApprove;
    }
    
}
