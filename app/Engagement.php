<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Engagement extends Model
{
    protected $table = 'engagement';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
