<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTasks extends Model
{
  protected $table = 'user_tasks';

  protected $fillable = [
    'user_id',
    'client_id',
    'project_id',
    'task_id',
    'timer',
    'started_at',
    'timer_date',
    'is_running',
    'notes',
    'is_weekly_only'
  ];

  protected $dates = ['started_at'];
  /**
   * Get the user who created the case type.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function client(): BelongsTo
  {
    return $this->belongsTo(Client::class, 'client_id', 'id');
  }

  /**
   * Get the user who created the case type.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function project(): BelongsTo
  {
    return $this->belongsTo(Project::class, 'project_id', 'id');
  }

  /**
   * Get the user who created the case type.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function task(): BelongsTo
  {
    return $this->belongsTo(TaskName::class, 'task_id', 'id');
  }

  /**
   * Get the user who created the case type.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function username(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }
}
