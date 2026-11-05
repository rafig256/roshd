<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Enums\EventStatus;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['speakers.user', 'categories'])
            ->where('status', EventStatus::Published)
            ->latest('starts_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $filter = $request->input('filter', 'all');
        if ($filter === 'upcoming') {
            $query->where(function ($q) {
                $q->whereNull('ends_at')
                  ->orWhere('ends_at', '>=', now());
            });
        } elseif ($filter === 'past') {
            $query->where('ends_at', '<', now());
        }

        $events = $query->paginate(9)->withQueryString();

        return view('public.events.index', compact('events', 'filter'));
    }

    public function show(Event $event)
    {
        if ($event->status !== EventStatus::Published) {
            abort(404);
        }

        $event->load([
            'speakers.user',
            'categories',
            'sessions' => function ($q) {
                $q->orderBy('order', 'asc');
            }
        ]);

        $isRegistered = auth()->check() && $event->registrations()->where('user_id', auth()->id())->exists();

        return view('public.events.show', compact('event', 'isRegistered'));
    }
}