<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'client';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
