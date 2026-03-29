<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\News;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class NewsControllerTest extends TestCase
{
    use RefreshDatabase;

    private Admin $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Role::query()->firstOrCreate(['id' => 1], ['name' => 'Admin', 'permissions' => null]);
        $this->admin = Admin::query()->firstOrCreate(
            ['email' => 'newsadmin@test.local'],
            [
                'name' => 'News Test Admin',
                'password' => Hash::make('password'),
                'user_type' => 1,
                'joined' => now(),
            ]
        );
        Storage::fake('public');
    }

    public function test_guest_cannot_see_news_index(): void
    {
        $response = $this->get(route('admin.news.index'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_see_news_index(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.news.index'));
        $response->assertOk();
        $response->assertViewIs('admin.news.index');
    }

    public function test_admin_can_create_news_without_image(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.news.store'), [
            'title' => 'Test News',
            'category' => 'General',
            'content' => 'Body content here.',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('news', [
            'title' => 'Test News',
            'category' => 'General',
            'content' => 'Body content here.',
            'imagelocation' => 'default.png',
        ]);
    }

    public function test_admin_can_create_news_with_image(): void
    {
        $file = UploadedFile::fake()->image('cover.jpg', 600, 400);

        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.news.store'), [
            'title' => 'News With Cover',
            'category' => 'Events',
            'content' => 'Content.',
            'photoimg' => $file,
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('news', ['title' => 'News With Cover']);
        $news = News::query()->where('title', 'News With Cover')->firstOrFail();
        $this->assertNotNull($news->imagelocation);
        $this->assertNotSame('default.png', $news->imagelocation);
    }

    public function test_admin_can_see_news_show(): void
    {
        $news = News::query()->create([
            'newsid' => (string) Str::uuid(),
            'title' => 'Show Me',
            'slug' => 'show-me',
            'content' => 'Content',
            'category' => 'Cat',
            'author' => $this->admin->name,
            'imagelocation' => 'default.png',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.news.show', $news->id));
        $response->assertOk();
        $response->assertSee('Show Me');
    }

    public function test_show_returns_404_for_invalid_id(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->get(route('admin.news.show', 999_999));
        $response->assertNotFound();
    }

    public function test_admin_can_update_news(): void
    {
        $news = News::query()->create([
            'newsid' => (string) Str::uuid(),
            'title' => 'Original',
            'slug' => 'original',
            'content' => 'Old content',
            'category' => 'Cat',
            'author' => $this->admin->name,
            'imagelocation' => 'default.png',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->put(route('admin.news.update', $news->id), [
            'newsId' => $news->id,
            'title' => 'Updated Title',
            'category' => 'NewCat',
            'content' => 'Updated body.',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('news', [
            'newsid' => $news->newsid,
            'title' => 'Updated Title',
            'category' => 'NewCat',
            'content' => 'Updated body.',
        ]);
    }

    public function test_admin_can_delete_news(): void
    {
        $news = News::query()->create([
            'newsid' => (string) Str::uuid(),
            'title' => 'To Delete',
            'slug' => 'to-delete',
            'content' => 'Content',
            'category' => 'Cat',
            'author' => $this->admin->name,
            'imagelocation' => 'default.png',
            'image' => 'default.png',
        ]);

        $response = $this->actingAs($this->admin, 'admin')->delete(route('admin.news.destroy', $news->id));
        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseMissing('news', ['newsid' => $news->newsid]);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin, 'admin')->post(route('admin.news.store'), [
            'title' => '',
            'category' => '',
            'content' => '',
            '_token' => csrf_token(),
        ]);

        $response->assertSessionHasErrors(['title', 'category', 'content']);
    }
}
