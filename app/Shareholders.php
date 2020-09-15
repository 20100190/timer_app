<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shareholders extends Model
{
    protected $table = 'shareholders';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
