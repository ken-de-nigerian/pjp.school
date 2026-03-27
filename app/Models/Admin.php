<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property mixed $adminId
 * @property mixed $user_type
 * @property mixed $role
 */
class Admin extends Authenticatable
{
    public $timestamps = false;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $table = 'admin';

    protected $primaryKey = 'adminId';

    public const UPDATED_AT = null;

    public const CREATED_AT = null;

    protected $fillable = [
        'adminId',
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

    public function getAuthIdentifierName(): string
    {
        return 'adminId';
    }

    public function getAuthPassword(): string
    {
        return $this->password;
    }

    /**
     * Check if this admin has a role permission.
     * Super admin (user_type === 1) has all permissions.
     * Otherwise, the role's permission column must equal 1.
     */
    public function hasPermission(string $key): bool
    {
        if ((int) $this->user_type === 1) {
            return true;
        }

        $role = $this->relationLoaded('role') ? $this->role : $this->role()->first();

        return $role !== null && (int) ($role->{$key} ?? 0) === 1;
    }
}
