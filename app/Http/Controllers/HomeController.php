<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Event;
use App\Enums\EventStatus;

class HomeController extends Controller
{
    public function index()
    {
        $latestNews = News::with(['categories', 'author'])
            ->where('status', 'published')
            ->latest('published_at')
            ->take(3)
            ->get();

        $homeEvents = Event::where('status', EventStatus::Published)
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at', 'asc')
            ->take(6)
            ->get();

        return view('home', compact('latestNews', 'homeEvents'));
    }
}