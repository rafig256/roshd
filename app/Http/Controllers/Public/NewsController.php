<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Enums\NewsStatus;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with(['categories', 'author'])
            ->where('status', NewsStatus::Published)
            ->latest('published_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $newsItems = $query->paginate(9)->withQueryString();
        $categories = NewsCategory::where('is_active', true)->get();

        return view('public.news.index', compact('newsItems', 'categories'));
    }

    public function show(News $news)
    {
        if ($news->status !== NewsStatus::Published) {
            abort(404);
        }

        $news->increment('views_count');

        return view('public.news.show', compact('news'));
    }
}