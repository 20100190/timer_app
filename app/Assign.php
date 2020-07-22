<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assign extends Model
{
    protected $table = 'assign';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
