<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectPhaseItem extends Model
{
    protected $table = 'project phase item';    
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
