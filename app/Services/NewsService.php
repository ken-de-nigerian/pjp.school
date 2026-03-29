<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\News;
use App\Support\Coercion;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Schema;

final class NewsService
{
    /**
     * News index: featured article separated from paginated list items.
     *
     * @return array{featured: News|null, paginated: LengthAwarePaginator<int, News>}
     */
    public function forNewsIndex(int $perPage = 9): array
    {
        $featuredQuery = News::query();

        if (Schema::hasColumn('news', 'created_at')) {
            $featuredQuery->orderByDesc('created_at');
        } else {
            $featuredQuery->orderByDesc('date_added');
        }

        $featured = $featuredQuery->first();

        $listQuery = News::query();
        if ($featured) {
            $listQuery->whereKeyNot($featured->getKey());
        }

        if (Schema::hasColumn('news', 'created_at')) {
            $listQuery->orderByDesc('created_at');
        } else {
            $listQuery->orderByDesc('date_added');
        }

        return [
            'featured' => $featured,
            'paginated' => $listQuery->paginate($perPage),
        ];
    }

    /** @return LengthAwarePaginator<int, News> */
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

    /** @param array<string, mixed> $data */
    public function createWithImage(array $data, string $author, string $imageFileName): News
    {
        $title = Coercion::string($data['title'] ?? '');
        $slug = Str::slug($title);

        return News::query()->create([
            'newsid' => Str::uuid(),
            'title' => $title,
            'slug' => $slug,
            'imagelocation' => $imageFileName,
            'content' => Coercion::string($data['content'] ?? ''),
            'category' => Coercion::string($data['category'] ?? ''),
            'author' => $author,
        ]);
    }

    /** @param array<string, mixed> $data */
    public function createNoImage(array $data, string $author): News
    {
        $title = Coercion::string($data['title'] ?? '');
        $slug = Str::slug($title);

        return News::query()->create([
            'newsid' => Str::uuid(),
            'title' => $title,
            'slug' => $slug,
            'imagelocation' => 'default.png',
            'content' => Coercion::string($data['content'] ?? ''),
            'category' => Coercion::string($data['category'] ?? ''),
            'author' => $author,
        ]);
    }

    /** @param array<string, mixed> $data */
    public function update(int|string $id, array $data, string $author): int
    {
        $title = Coercion::string($data['title'] ?? '');
        $slug = Str::slug($title);

        return News::query()->where('id', $id)->update([
            'title' => $title,
            'slug' => $slug,
            'content' => Coercion::string($data['content'] ?? ''),
            'category' => Coercion::string($data['category'] ?? ''),
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
        $deleted = News::query()->where('id', $id)->delete();

        return is_int($deleted) ? $deleted : 0;
    }
}
