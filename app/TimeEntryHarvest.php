<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeEntryHarvest extends Model
{
    protected $table = 'time_entry_harvest';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
