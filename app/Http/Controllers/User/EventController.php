<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = EventRegistration::with(['event.categories', 'event.speakers.user'])
            ->where('user_id', auth()->id())
            ->latest('registered_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('event', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        $registrations = $query->paginate(10)->withQueryString();

        return view('user.events.index', compact('registrations'));
    }
}