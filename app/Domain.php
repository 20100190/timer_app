<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $table = 'domain';    
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
    
}
