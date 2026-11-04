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
            ->orderBy('starts_at', 'asc')
            ->take(6)
            ->get();
            // dd($homeEvents);
        return view('home', compact('latestNews', 'homeEvents'));
    }
}