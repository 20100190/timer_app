<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';
    protected $guarded = ['id'];
   //タイムスタンプの更新を無効にする
    public $timestamps = false;

    public function scopeGetClientGroup($query) {               
        $clientGroup = $query->select("group_companies")->groupBy("group_companies")->where([["group_companies","<>",""]])->get();
                
        return $clientGroup;
    }
}
