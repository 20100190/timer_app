<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientHarvest extends Model
{
    protected $table = 'client_harvest';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
