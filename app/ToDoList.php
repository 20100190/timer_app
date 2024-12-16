<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToDoList extends Model
{
    protected $table = 'to_do_list';
    //タイムスタンプの更新を無効
    public $timestamps = false;
}
