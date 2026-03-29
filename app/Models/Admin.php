<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Coercion;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property int $id
 * @property mixed $user_type
 * @property mixed $role
 */
class Admin extends Authenticatable
{
    public $timestamps = false;

    protected $table = 'admin';

    public const UPDATED_AT = null;

    public const CREATED_AT = null;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'profileImage',
        'user_type',
        'security',
        'joined',
        'password_change_date',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'joined' => 'datetime',
            'password_change_date' => 'datetime',
        ];
    }

    /** @return BelongsTo<Role, $this> */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'user_type', 'id');
    }

    public function hasPermission(string $key): bool
    {
        if (Coercion::int($this->user_type) === 1) {
            return true;
        }

        $role = $this->relationLoaded('role') ? $this->role : $this->role()->first();

        return $role !== null && Coercion::int($role->{$key} ?? 0) === 1;
    }
}
