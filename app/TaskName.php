<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskName extends Model
{
    protected $table = 'task name';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
    protected $guarded = ['id'];
}
