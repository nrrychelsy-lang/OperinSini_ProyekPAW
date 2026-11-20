<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'role', 'password'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
    protected function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }
    protected function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function homeRoute(): string
    {
        return $this->isAdmin() ? 'admin.dashboard' : 'catalog.index';
    }
    protected function whatsappLink(string $text = ''): ?string
    {
        $n = preg_replace('/\D/', '', (string) $this->phone);
        if ($n === '') {
            return null;
        }
        if (str_starts_with($n, '0')) {
            $n = '62' . substr($n, 1);
        } else if (str_starts_with($n, '8')) {
            $n = '62' . $n;
        }
        return 'https://wa.me/' . $n . ($text ? '?text=' . rawurlencode($text) : '');
    }
}
