<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\News;
use App\Services\NewsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class NewsServiceTest extends TestCase
{
    use RefreshDatabase;

    private NewsService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new NewsService;
    }

    public function test_list_orders_by_created_at_desc_and_paginates(): void
    {
        News::query()->create([
            'newsid' => (string) Str::uuid(),
            'title' => 'First',
            'slug' => 'first',
            'content' => 'C',
            'category' => 'Cat',
            'author' => 'A',
            'imagelocation' => 'default.png',
            'created_at' => now()->subDay(),
        ]);
        News::query()->create([
            'newsid' => (string) Str::uuid(),
            'title' => 'Second',
            'slug' => 'second',
            'content' => 'C',
            'category' => 'Cat',
            'author' => 'A',
            'imagelocation' => 'default.png',
            'created_at' => now(),
        ]);

        $list = $this->service->list(5);
        $this->assertSame(2, $list->total());
        $items = $list->items();
        $this->assertSame('Second', $items[0]->title);
    }

    public function test_get_by_id_returns_null_for_missing(): void
    {
        $this->assertNull($this->service->getById(999_999));
    }

    public function test_get_by_id_returns_news(): void
    {
        $news = News::query()->create([
            'newsid' => (string) Str::uuid(),
            'title' => 'One',
            'slug' => 'one',
            'content' => 'C',
            'category' => 'Cat',
            'author' => 'A',
            'imagelocation' => 'default.png',
        ]);

        $found = $this->service->getById($news->id);
        $this->assertNotNull($found);
        $this->assertSame('One', $found->title);
    }

    public function test_has_news_id(): void
    {
        $news = News::query()->create([
            'newsid' => (string) Str::uuid(),
            'title' => 'H',
            'slug' => 'h',
            'content' => 'C',
            'category' => 'Cat',
            'author' => 'A',
            'imagelocation' => 'default.png',
        ]);

        $this->assertTrue($this->service->hasNewsId($news->id));
        $this->assertFalse($this->service->hasNewsId(999_999));
    }

    public function test_create_no_image(): void
    {
        $created = $this->service->createNoImage([
            'title' => 'No Img',
            'category' => 'Cat',
            'content' => 'Body',
        ], 'Author Name');

        $this->assertInstanceOf(News::class, $created);
        $this->assertSame('No Img', $created->title);
        $this->assertSame('default.png', $created->imagelocation);
        $this->assertSame('no-img', $created->slug);
    }

    public function test_create_with_image(): void
    {
        $created = $this->service->createWithImage([
            'title' => 'With Img',
            'category' => 'Cat',
            'content' => 'Body',
        ], 'Author', 'custom.jpg');

        $this->assertSame('With Img', $created->title);
        $this->assertSame('custom.jpg', $created->imagelocation);
    }

    public function test_update(): void
    {
        $news = News::query()->create([
            'newsid' => (string) Str::uuid(),
            'title' => 'Old',
            'slug' => 'old',
            'content' => 'Old content',
            'category' => 'Cat',
            'author' => 'A',
            'imagelocation' => 'default.png',
        ]);

        $count = $this->service->update($news->id, [
            'title' => 'New Title',
            'category' => 'NewCat',
            'content' => 'New body',
        ], 'New Author');

        $this->assertSame(1, $count);
        $news->refresh();
        $this->assertSame('New Title', $news->title);
        $this->assertSame('New body', $news->content);
        $this->assertSame('NewCat', $news->category);
    }

    public function test_update_cover_image(): void
    {
        $news = News::query()->create([
            'newsid' => (string) Str::uuid(),
            'title' => 'T',
            'slug' => 't',
            'content' => 'C',
            'category' => 'Cat',
            'author' => 'A',
            'imagelocation' => 'default.png',
        ]);

        $count = $this->service->updateCoverImage($news->id, 'new-cover.jpg');
        $this->assertSame(1, $count);
        $news->refresh();
        $this->assertSame('new-cover.jpg', $news->imagelocation);
    }

    public function test_delete(): void
    {
        $news = News::query()->create([
            'newsid' => (string) Str::uuid(),
            'title' => 'Del',
            'slug' => 'del',
            'content' => 'C',
            'category' => 'Cat',
            'author' => 'A',
            'imagelocation' => 'default.png',
        ]);

        $count = $this->service->delete($news->id);
        $this->assertSame(1, $count);
        $this->assertDatabaseMissing('news', ['newsid' => $news->newsid]);
    }
}
