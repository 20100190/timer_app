<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConvertProjectId extends Model
{
    protected $table = 'convert_project_id';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
