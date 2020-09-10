<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    protected $table = 'phase';    
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
