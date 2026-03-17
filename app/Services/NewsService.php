<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Schema;

class NewsService
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
        return News::query()->where('id', $id)->first();
    }

    public function hasNewsId(int|string $id): bool
    {
        return News::query()->where('id', $id)->exists();
    }

    public function createWithImage(array $data, string $author, string $imageFileName): News
    {
        $slug = Str::slug($data['title'] ?? '');
        return News::query()->create([
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'] ?? $data['message'] ?? '',
            'category' => $data['category'] ?? '',
            'author' => $author,
            'imagelocation' => $imageFileName,
            'image' => $imageFileName,
        ]);
    }

    public function createNoImage(array $data, string $author): News
    {
        $slug = Str::slug($data['title'] ?? '');
        return News::query()->create([
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'] ?? $data['message'] ?? '',
            'category' => $data['category'] ?? '',
            'author' => $author,
            'imagelocation' => 'default.png',
            'image' => 'default.png',
        ]);
    }

    public function update(int|string $newsid, array $data, string $author): int
    {
        $slug = Str::slug($data['title'] ?? '');
        return News::query()->where('newsid', $newsid)->update([
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'] ?? $data['message'] ?? '',
            'category' => $data['category'] ?? '',
            'author' => $author,
        ]);
    }

    public function updateCoverImage(int|string $newsid, string $fileName): int
    {
        return News::query()->where('newsid', $newsid)->update([
            'imagelocation' => $fileName,
            'image' => $fileName,
        ]);
    }

    public function delete(int|string $newsid): int
    {
        return (int) News::query()->where('newsid', $newsid)->delete();
    }
}
