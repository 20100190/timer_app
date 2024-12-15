<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTasks extends Model
{
  protected $table = 'user_tasks';

  protected $fillable = [
    'username',
    'client_id',
    'project_id',
    'client_name',
    'project_name',
    'timer',
    'started_at',
    'timer_date',
    'is_running'
  ];

  protected $dates = ['started_at'];
}