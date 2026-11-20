<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    protected $fillable = ['item_id', 'user_id', 'reason', 'status'];
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
