<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    /**
     * List news (ported from pages/news.php).
     * ?featured=1 returns the single headline article.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $news = News::query()
            ->when($request->boolean('featured'), fn ($q) => $q->featured())
            ->orderBy('date_published', 'desc')
            ->paginate($request->integer('per_page', 10));

        return NewsResource::collection($news);
    }

    /**
     * Show a single article (ported from pages/news-page.php).
     */
    public function show(News $news): NewsResource
    {
        return new NewsResource($news);
    }

    /**
     * Add a news article with image upload (ported from admin/admin-add-news.php).
     */
    public function store(StoreNewsRequest $request, FileUploadService $uploader): JsonResponse
    {
        $image = $uploader->storeImage($request->file('image'), 'news');

        $news = News::create([
            ...$request->validated(),
            'image' => $image,
            'is_featured' => false,
            'user_id' => $request->user()->id,
        ]);

        return (new NewsResource($news))->response()->setStatusCode(201);
    }

    /**
     * Update a news article (ported from admin/admin-manage-news.php).
     */
    public function update(UpdateNewsRequest $request, News $news, FileUploadService $uploader): NewsResource
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $uploader->deleteImage('news', $news->image);
            $data['image'] = $uploader->storeImage($request->file('image'), 'news');
        }

        $news->update($data);

        return new NewsResource($news->fresh());
    }

    /**
     * Delete a news article and its image (ported from admin/admin-manage-news.php).
     */
    public function destroy(News $news, FileUploadService $uploader): JsonResponse
    {
        $uploader->deleteImage('news', $news->image);
        $news->delete();

        return response()->json(['message' => 'Record deleted successfully']);
    }

    /**
     * Set a single headline: clear is_featured everywhere, then feature the
     * selected article (ported from admin/admin-manage-news.php).
     */
    public function setFeatured(News $news): JsonResponse
    {
        DB::transaction(function () use ($news) {
            News::query()->whereKeyNot($news->id)->update(['is_featured' => false]);
            $news->update(['is_featured' => true]);
        });

        return response()->json(['message' => 'Set as Headline']);
    }
}
