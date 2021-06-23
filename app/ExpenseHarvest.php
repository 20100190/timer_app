<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseHarvest extends Model
{
    protected $table = 'expense_harvest';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
