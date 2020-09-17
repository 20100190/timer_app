<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhaseItems extends Model
{
    protected $table = 'phase items';    
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
