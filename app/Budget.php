<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $table = 'budget';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
