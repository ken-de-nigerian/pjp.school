<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\News;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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

    /**
     * Guest home: most recent item for the featured block, remaining for the list column.
     *
     * @return array{featured: News|null, more: Collection<int, News>}
     */
    public function forHomePage(int $additionalListCount = 5): array
    {
        $query = News::query();

        if (Schema::hasColumn('news', 'created_at')) {
            $query->orderByDesc('created_at');
        } else {
            $query->orderByDesc('date_added');
        }

        $take = 1 + max(0, $additionalListCount);
        $all = $query->limit($take)->get();

        return [
            'featured' => $all->first(),
            'more' => $all->slice(1)->values(),
        ];
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

        return News::query()->where('id', $id)->update([
            'title' => $data['title'],
            'slug' => $slug,
            'content' => $data['content'],
            'category' => $data['category'] ?? '',
            'author' => $author,
        ]);
    }

    public function updateCoverImage(int|string $id, string $fileName): int
    {
        return News::query()->where('id', $id)->update([
            'imagelocation' => $fileName,
        ]);
    }

    public function delete(int|string $id): int
    {
        return (int) News::query()->where('id', $id)->delete();
    }
}
