<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NewsController extends Controller
{
    /**
     * Display a listing of published news articles.
     */
    public function index(Request $request): JsonResponse
    {
        $query = News::published()
            ->with('author:id,name')
            ->orderBy('published_at', 'desc');

        // Filter by featured if requested
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Pagination
        $perPage = min($request->integer('per_page', 10), 50);
        $news = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $news->items(),
            'meta' => [
                'current_page' => $news->currentPage(),
                'last_page' => $news->lastPage(),
                'per_page' => $news->perPage(),
                'total' => $news->total(),
            ],
        ]);
    }

    /**
     * Display the specified news article.
     */
    public function show(string $slug): JsonResponse
    {
        $news = News::where('slug', $slug)
            ->published()
            ->with('author:id,name')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $news,
        ]);
    }

    /**
     * Get latest news articles (for homepage/widgets).
     */
    public function latest(Request $request): JsonResponse
    {
        $limit = min($request->integer('limit', 5), 20);
        
        $news = News::published()
            ->with('author:id,name')
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $news,
        ]);
    }

    /**
     * Get featured news articles.
     */
    public function featured(Request $request): JsonResponse
    {
        $limit = min($request->integer('limit', 3), 10);
        
        $news = News::published()
            ->featured()
            ->with('author:id,name')
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $news,
        ]);
    }
}
