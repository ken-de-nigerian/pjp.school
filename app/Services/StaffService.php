<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Random\RandomException;

final class StaffService
{
    /** @return LengthAwarePaginator<int, Admin> */
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return Admin::query()
            ->with('role')
            ->orderBy('joined')
            ->paginate($perPage);
    }

    /** @return Collection<int, Role> */
    public function getAllRoles(): Collection
    {
        return Role::query()->orderBy('name')->get();
    }

    public function hasAdminEmail(string $email, ?string $excludeAdminId = null): bool
    {
        $q = Admin::query()->where('email', $email);
        if ($excludeAdminId !== null) {
            $q->where('adminId', '!=', $excludeAdminId);
        }

        return $q->exists();
    }

    /** @param array<string, mixed> $data
     * @throws RandomException
     */
    public function create(array $data): Admin
    {
        $adminId = $this->generateAdminId();

        return Admin::query()->create([
            'adminId' => $adminId,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'profileImage' => 'default.png',
            'joined' => now()->format('Y-m-d H:i:s'),
            'user_type' => (int) $data['user_type'],
            'security' => 0,
        ]);
    }

    /** @param array<string, mixed> $data */
    public function update(string $adminId, array $data): int
    {
        return Admin::query()->where('adminId', $adminId)->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'user_type' => (int) $data['user_type'],
        ]);
    }

    public function resetPassword(string $adminId, string $hashedPassword): int
    {
        return Admin::query()->where('adminId', $adminId)->update([
            'password' => $hashedPassword,
            'password_change_date' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    /** @return Collection<int, Admin> */
    public function search(Request $request): Collection
    {
        $validated = $request->validate([
            'search' => 'required|string|min:2|max:255',
        ]);

        $term = '%'.$validated['search'].'%';

        return Admin::query()
            ->with('role')
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term);
            })
            ->orderBy('joined', 'desc')
            ->get();
    }

    public function delete(string $adminId): bool
    {
        return (bool) Admin::query()->where('adminId', $adminId)->delete();
    }

    /** Generate unique admin ID (legacy: 12-digit numeric string).
     * @throws RandomException
     */
    public function generateAdminId(): string
    {
        return substr((int) (microtime(true) * 1000).random_int(100, 999), 0, 12);
    }
}
