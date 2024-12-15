<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutlookAccessToken extends Model
{
    protected $table = 'outlook_access_token';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
