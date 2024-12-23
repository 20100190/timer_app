<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'client';
    protected $guarded = ['id'];
    //タイムスタンプの更新を無効にする
    public $timestamps = false;

    public function scopeGetClientGroup($query)
    {
        $clientGroup = $query->select("group_companies")->groupBy("group_companies")->where([["group_companies", "<>", ""]])->get();

        return $clientGroup;
    }
    public function projects(): HasMany
    {

        return $this->hasMany(Project::class, 'client_id');
    }
}
