<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with(['author', 'categories'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $news = $query->paginate(10)->withQueryString();

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        $categories = NewsCategory::where('is_active', true)->get();
        return view('admin.news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'categories'       => 'nullable|array',
            'categories.*'     => 'exists:news_categories,id',
            'excerpt'          => 'nullable|string',
            'body'             => 'required|string',
            'cover_image'      => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status'           => 'required|in:draft,published,archived',
            'is_featured'      => 'boolean',
        ]);

        $slug = Str::slug($validated['title']);
        // Ensure unique slug
        $originalSlug = $slug;
        $counter = 1;
        while (News::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('news-covers', 'public');
        }

        $publishedAt = null;
        if ($validated['status'] === 'published' && $request->published_at === null) {
            $publishedAt = now();
        }

        News::create([
            'slug'              => $slug,
            'title'             => $validated['title'],
            'excerpt'           => $validated['excerpt'] ?? null,
            'body'              => $validated['body'],
            'cover_image_path'  => $coverPath,
            'author_id'         => auth()->id(),
            'status'            => $validated['status'],
            'published_at'      => $publishedAt,
            'is_featured'       => $request->boolean('is_featured'),
        ]);

        $news = News::where('slug', $slug)->first();
        if ($news && !empty($validated['categories'])) {
            $news->categories()->sync($validated['categories']);
        }

        return redirect()->route('admin.news.index')
            ->with('success', 'خبر با موفقیت ایجاد شد.');
    }

    public function edit(News $news)
    {
        $news->load('categories');
        $categories = NewsCategory::where('is_active', true)->get();
        return view('admin.news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'categories'       => 'nullable|array',
            'categories.*'     => 'exists:news_categories,id',
            'excerpt'          => 'nullable|string',
            'body'             => 'required|string',
            'cover_image'      => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'status'           => 'required|in:draft,published,archived',
            'is_featured'      => 'boolean',
        ]);

        $slug = Str::slug($validated['title']);
        // Ensure unique slug (excluding current news)
        $originalSlug = $slug;
        $counter = 1;
        while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $coverPath = $news->cover_image_path;
        if ($request->hasFile('cover_image')) {
            if ($coverPath && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $request->file('cover_image')->store('news-covers', 'public');
        }

        $publishedAt = $news->published_at;
        if ($validated['status'] === 'published' && !$publishedAt) {
            $publishedAt = now();
        }

        $news->update([
            'slug'              => $slug,
            'title'             => $validated['title'],
            'excerpt'           => $validated['excerpt'] ?? null,
            'body'              => $validated['body'],
            'cover_image_path'  => $coverPath,
            'status'            => $validated['status'],
            'published_at'      => $publishedAt,
            'is_featured'       => $request->boolean('is_featured'),
        ]);

        $news->categories()->sync($validated['categories'] ?? []);

        return redirect()->route('admin.news.index')
            ->with('success', 'خبر با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(News $news)
    {
        if ($news->cover_image_path && Storage::disk('public')->exists($news->cover_image_path)) {
            Storage::disk('public')->delete($news->cover_image_path);
        }

        $news->categories()->detach();
        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'خبر با موفقیت حذف شد.');
    }
}