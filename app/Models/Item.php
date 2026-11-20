<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Item extends Model
{
    public const CONDITIONS = ['Sangat Baik', 'Baik', 'Cukup'];

    protected $fillable = ['user_id', 'category_id', 'name', 'descriptiion', 'condition', 'status'];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function images(): HasMany
    {
        return $this->hasMany(ItemImage::class)->orderBy('sort')->orderBy('id');
    }
    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
    public function scopeAvailable(Builder $q): Builder
    {
        return $q->where('status', 'tersedia');
    }
    public function getCoverUrlAttribute(): ?string
    {
        return $this->images->first()?->url;
    }
}
