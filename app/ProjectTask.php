<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    protected $table = 'project task';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
