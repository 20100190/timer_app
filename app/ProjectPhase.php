<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectPhase extends Model
{
    protected $table = 'project phase';    
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
