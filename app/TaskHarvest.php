<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskHarvest extends Model
{
    protected $table = 'task_harvest';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
