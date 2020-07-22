<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    protected $table = 'week';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
