<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsCategory::with('parent')
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $categories = $query->paginate(10)->withQueryString();

        return view('admin.news-categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = NewsCategory::whereNull('parent_id')->get();
        return view('admin.news-categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'parent_id'   => 'nullable|exists:news_categories,id',
            'description' => 'nullable|string',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        NewsCategory::create($validated);

        return redirect()->route('admin.news-categories.index')
            ->with('success', 'دسته‌بندی با موفقیت ایجاد شد.');
    }

    public function edit(NewsCategory $newsCategory)
    {
        $parentCategories = NewsCategory::whereNull('parent_id')
            ->where('id', '!=', $newsCategory->id)
            ->get();

        return view('admin.news-categories.edit', compact('newsCategory', 'parentCategories'));
    }

    public function update(Request $request, NewsCategory $newsCategory)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'parent_id'   => 'nullable|exists:news_categories,id',
            'description' => 'nullable|string',
            'order'       => 'nullable|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        $newsCategory->update($validated);

        return redirect()->route('admin.news-categories.index')
            ->with('success', 'دسته‌بندی با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(NewsCategory $newsCategory)
    {
        // Check if category has news attached
        if ($newsCategory->news()->exists()) {
            return back()->with('error', 'نمی‌توان این دسته‌بندی را حذف کرد، زیرا اخباری به آن متصل است.');
        }

        // Check if category has child categories
        if ($newsCategory->children()->exists()) {
            return back()->with('error', 'نمی‌توان این دسته‌بندی را حذف کرد، زیرا زیرمجموعه دارد.');
        }

        $newsCategory->delete();

        return redirect()->route('admin.news-categories.index')
            ->with('success', 'دسته‌بندی با موفقیت حذف شد.');
    }
}