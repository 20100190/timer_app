<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $table = 'project';
   //タイムスタンプの更新を無効にする
    public $timestamps = false;

    public function picInitial(): BelongsTo
    {
      return $this->belongsTo(Staff::class, 'pic', 'id');
    }
}
