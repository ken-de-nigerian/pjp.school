<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\NotificationServiceContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsCoverImageRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Models\News;
use App\Services\NewsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class NewsController extends Controller
{
    private const COVER_WIDTH = 556;

    private const COVER_HEIGHT = 350;

    public function __construct(
        private readonly NewsService $newsService,
        private readonly NotificationServiceContract $notificationService
    ) {}

    public function index(): View
    {
        Gate::authorize('viewAny', News::class);

        $perPage = 6;
        $news = $this->newsService->list($perPage);

        return view('admin.news.index', [
            'news' => $news,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', News::class);

        return view('admin.news.create');
    }

    public function store(StoreNewsRequest $request): JsonResponse|RedirectResponse
    {
        Gate::authorize('create', News::class);

        $author = $request->user('admin')->name ?? 'Admin';
        $data = $request->validated();

        try {
            if ($request->hasFile('photoimg') && $request->file('photoimg')->isValid()) {
                $fileName = $this->storeResizedCoverImage($request->file('photoimg'));
                if ($fileName === null) {
                    return $this->jsonError('Unable to save the image. Please try again.');
                }
                $news = $this->newsService->createWithImage($data, $author, $fileName);
            } else {
                $news = $this->newsService->createNoImage($data, $author);
            }

            $this->notificationService->add('News Added', $author.' has added a news: '.($data['title'] ?? ''));

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Your news has been posted successfully.',
                ]);
            }

            return redirect()->route('admin.news.show', $news)->with('success', 'News posted successfully.');
        } catch (Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
            }

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(News $news): View
    {
        Gate::authorize('view', $news);

        return view('admin.news.show', [
            'news' => $news,
        ]);
    }

    public function edit(News $news): View
    {
        Gate::authorize('update', $news);

        return view('admin.news.edit', ['news' => $news]);
    }

    public function update(UpdateNewsRequest $request, News $news): JsonResponse|RedirectResponse
    {
        Gate::authorize('update', $news);

        $author = $request->user('admin')->name ?? 'Admin';
        $data = $request->validated();

        try {
            $updated = $this->newsService->update($news->getKey(), $data, $author);
            if ($updated > 0) {
                $this->notificationService->add('News Edited', $author.' has edited a news: '.($data['title'] ?? ''));
            }
            if ($request->hasFile('photoimg') && $request->file('photoimg')->isValid()) {
                $fileName = $this->storeResizedCoverImage($request->file('photoimg'));
                if ($fileName !== null) {
                    $this->newsService->updateCoverImage($news->getKey(), $fileName);
                    $this->notificationService->add('News Cover Image Edited', $author.' has edited a news cover image');
                }
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => $updated > 0 ? 'success' : 'error',
                    'message' => $updated > 0 ? 'Your news has been updated successfully.' : 'No changes was made to the news.',
                ]);
            }

            return redirect()->route('admin.news.show', $news)->with('success', 'News updated successfully.');
        } catch (Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
            }

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request, News $news): JsonResponse|RedirectResponse
    {
        Gate::authorize('delete', $news);

        $deleted = $this->newsService->delete($news->getKey());
        if ($deleted > 0) {
            $adminName = $request->user('admin')->name ?? 'Admin';
            $this->notificationService->add('News Deleted', $adminName.' has deleted a news: '.$news->title);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $deleted > 0 ? 'success' : 'error',
                'message' => $deleted > 0 ? 'This news has been deleted successfully.' : 'Unable to delete this news. Please try again.',
            ]);
        }

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }

    public function updateCoverImage(UpdateNewsCoverImageRequest $request): JsonResponse
    {
        $id = $request->input('id');
        $news = $this->newsService->getById($id);
        if ($news === null) {
            return response()->json(['status' => 'error', 'message' => 'News not found.'], 404);
        }

        Gate::authorize('update', $news);

        $file = $request->file('photoimg');
        $fileName = $this->storeResizedCoverImage($file);
        if ($fileName === null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unable to save the image. Please try again.',
            ], 422);
        }

        $this->newsService->updateCoverImage($id, $fileName);
        $adminName = $request->user('admin')->name ?? 'Admin';
        $this->notificationService->add('News Cover Image Edited', $adminName.' has edited a news cover image');

        return response()->json(['status' => 'success']);
    }

    private function storeResizedCoverImage(UploadedFile $file): ?string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        if (! in_array($ext, ['jpg', 'jpeg', 'png'], true)) {
            return null;
        }

        $image = match ($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($file->getRealPath()),
            'png' => @imagecreatefrompng($file->getRealPath()),
            default => null,
        };
        if ($image === false || $image === null) {
            return null;
        }

        $resized = imagescale($image, self::COVER_WIDTH, self::COVER_HEIGHT);
        imagedestroy($image);
        if ($resized === false) {
            return null;
        }

        $fileName = uniqid('', true).'.jpg';
        $dir = 'news';
        Storage::disk('public')->makeDirectory($dir);
        $path = Storage::disk('public')->path($dir.'/'.$fileName);

        if (! imagejpeg($resized, $path, 90)) {
            imagedestroy($resized);

            return null;
        }
        imagedestroy($resized);

        return $fileName;
    }

    private function jsonError(string $message, int $code = 422): JsonResponse
    {
        return response()->json(['status' => 'error', 'message' => $message], $code);
    }
}
