<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Admin;
use App\Models\Role;
use App\Support\Coercion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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

    public function hasAdminEmail(string $email, ?int $excludeId = null): bool
    {
        $q = Admin::query()->where('email', $email);
        if ($excludeId !== null) {
            $q->where('id', '!=', $excludeId);
        }

        return $q->exists();
    }

    /** @param array<string, mixed> $data */
    public function create(array $data): Admin
    {
        return Admin::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => $data['password'],
            'profileImage' => 'default.png',
            'joined' => now()->format('Y-m-d H:i:s'),
            'user_type' => Coercion::int($data['user_type'] ?? 0),
            'security' => 0,
        ]);
    }

    /** @param array<string, mixed> $data */
    public function update(int $id, array $data): int
    {
        return Admin::query()->whereKey($id)->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'user_type' => Coercion::int($data['user_type'] ?? 0),
        ]);
    }

    public function resetPassword(int $id, string $hashedPassword): int
    {
        return Admin::query()->whereKey($id)->update([
            'password' => $hashedPassword,
            'password_change_date' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    /** @return Collection<int, Admin> */
    public function search(Request $request): Collection
    {
        $validated = Coercion::stringKeyedMap($request->validate([
            'search' => 'required|string|min:2|max:255',
        ]));

        $term = '%'.Coercion::string($validated['search'] ?? '').'%';

        return Admin::query()
            ->with('role')
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term);
            })
            ->orderBy('joined', 'desc')
            ->get();
    }

    public function delete(int $id): bool
    {
        return (bool) Admin::query()->whereKey($id)->delete();
    }
}
