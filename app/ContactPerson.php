<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactPerson extends Model
{
    protected $table = 'contact person';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
