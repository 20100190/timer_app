<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserHarvest extends Model
{
    protected $table = 'user_harvest';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
