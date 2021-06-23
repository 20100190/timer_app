<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EngagementHarvest extends Model
{
    protected $table = 'engagement_harvest';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
