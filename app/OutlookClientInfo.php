<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutlookClientInfo extends Model
{
    protected $table = 'outlook_client_info';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
