<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceHarvest extends Model
{
    protected $table = 'invoice_harvest';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;
}
