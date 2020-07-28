<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
