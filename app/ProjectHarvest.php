<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectHarvest extends Model
{
    protected $table = 'project_harvest';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
