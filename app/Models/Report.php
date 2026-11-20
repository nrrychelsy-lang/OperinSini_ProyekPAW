<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $fillable = ['item_id', 'user_id', 'reason', 'status'];
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
