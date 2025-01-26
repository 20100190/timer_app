<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
  protected $table = 'invoices';

  protected $fillable = [
        'date',
        'user_id',
        'client_id',
        'project_id',
        'type',
        'amount',
        'billable',
        'unreported',
        'invoiced'
    ];

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
  public function username(): BelongsTo
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

    public function files()
    {
        return $this->hasMany(InvoiceFile::class);
    }
}
