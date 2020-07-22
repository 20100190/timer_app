<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'project';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
