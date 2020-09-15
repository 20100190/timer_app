<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Officers extends Model
{
    protected $table = 'officers';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
