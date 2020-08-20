<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{
    protected $table = 'project_type';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
    
}
