<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Schema;

final class NewsService
{
    public function list(int $perPage = 6): LengthAwarePaginator
    {
        $query = News::query();

        if (Schema::hasColumn('news', 'created_at')) {
            $query->orderByDesc('created_at');
        } else {
            $query->orderByDesc('date_added');
        }

        return $query->paginate($perPage);
    }

    public function getById(int|string $id): ?News
    {
        return News::query()->where('newsid', $id)->first();
    }

    public function hasNewsId(int|string $id): bool
    {
        return News::query()->where('newsid', $id)->exists();
    }

    public function createWithImage(array $data, string $author, string $imageFileName): News
    {
        $slug = Str::slug($data['title'] ?? '');

        return News::query()->create([
            'newsid' => Str::uuid(),
            'title' => $data['title'],
            'slug' => $slug,
            'imagelocation' => $imageFileName,
            'content' => $data['content'],
            'category' => $data['category'] ?? '',
            'author' => $author,
        ]);
    }

    public function createNoImage(array $data, string $author): News
    {
        $slug = Str::slug($data['title'] ?? '');

        return News::query()->create([
            'newsid' => Str::uuid(),
            'title' => $data['title'],
            'slug' => $slug,
            'imagelocation' => 'default.png',
            'content' => $data['content'],
            'category' => $data['category'] ?? '',
            'author' => $author,
        ]);
    }

    public function update(int|string $id, array $data, string $author): int
    {
        $slug = Str::slug($data['title'] ?? '');

        return News::query()->where('newsid', $id)->update([
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'],
            'category' => $data['category'] ?? '',
            'author' => $author,
        ]);
    }

    public function updateCoverImage(int|string $id, string $fileName): int
    {
        return News::query()->where('newsid', $id)->update([
            'imagelocation' => $fileName,
        ]);
    }

    public function delete(int|string $id): int
    {
        return (int) News::query()->where('newsid', $id)->delete();
    }
}
