<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'two_factor_secret', 'phone', 'position', 'department', 'bio', 'avatar', 'role'])]
#[Hidden(['password', 'remember_token', 'two_factor_secret'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function preferences(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    public function getProfileStrengthAttribute(): int
    {
        $fields = ['name', 'email', 'phone', 'position', 'bio', 'avatar'];
        $filled = collect($fields)->filter(fn ($field) => !empty($this->{$field}))->count();

        return (int) round(($filled / count($fields)) * 100);
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = is_array($roles) ? $roles : func_get_args();
        return in_array($this->role, $roles, true) || $this->role === 'System Admin' || $this->role === 'Admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['System Admin', 'Admin'], true);
    }

    public function isManager(): bool
    {
        return $this->role === 'Manager' || $this->isAdmin();
    }

    public function isWarehouse(): bool
    {
        return in_array($this->role, ['Warehouse Manager', 'Picker', 'Packer', 'Verifier'], true) || $this->isAdmin();
    }

    public function isCashier(): bool
    {
        return $this->role === 'Cashier' || $this->isAdmin();
    }
}
