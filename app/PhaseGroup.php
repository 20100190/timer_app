<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhaseGroup extends Model
{
    protected $table = 'phase group';    
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
