<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceFile extends Model
{
    protected $table = 'invoice_files';

    protected $fillable = ['invoice_id', 'file_path', 'file_type'];

    public function invoice() {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'id');
    }
}
