<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

/**
 * Replicates legacy Krak\Models\News: getNews, countAll, addNews, addNewsNoImage,
 * hasNewsId, getNewsById, updateNewsCoverImage, editNews. Plus delete (legacy Requests::deleteNews).
 */
class NewsService
{
    public function list(int $perPage = 6): LengthAwarePaginator
    {
        $query = News::query();

        if (\Schema::hasColumn('news', 'created_at')) {
            $query->orderByDesc('created_at');
        } else {
            $query->orderByDesc('date_added');
        }

        return $query->paginate($perPage);
    }

    public function countAll(): int
    {
        return News::query()->count();
    }

    public function getById(int|string $newsid): ?News
    {
        return News::query()->where('newsid', $newsid)->first();
    }

    public function hasNewsId(int|string $newsid): bool
    {
        return News::query()->where('newsid', $newsid)->exists();
    }

    /** Create with cover image (filename). Legacy: addNews. */
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

    /** Create without cover image. Legacy: addNewsNoImage. */
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

    /** Legacy: editNews. */
    public function update(int|string $newsid, array $data, string $author): int
    {
        $slug = Str::slug($data['title'] ?? '');
        return (int) News::query()->where('newsid', $newsid)->update([
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'] ?? $data['message'] ?? '',
            'category' => $data['category'] ?? '',
            'author' => $author,
        ]);
    }

    /** Legacy: updateNewsCoverImage. */
    public function updateCoverImage(int|string $newsid, string $fileName): int
    {
        return (int) News::query()->where('newsid', $newsid)->update([
            'imagelocation' => $fileName,
            'image' => $fileName,
        ]);
    }

    /** Legacy: Requests::deleteNews (by newsid). */
    public function delete(int|string $newsid): int
    {
        return (int) News::query()->where('newsid', $newsid)->delete();
    }
}
