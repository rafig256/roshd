<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventSpeaker;
use App\Models\EventSession;
use App\Models\User;
use App\Enums\EventStatus;
use App\Enums\EventLocationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['creator', 'categories'])
            ->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $events = $query->paginate(10)->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $users = User::orderBy('name')->get();
        $categories = EventCategory::where('is_active', true)->get();
        return view('admin.events.create', compact('users', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                => 'required|string|max:255',
            'slug'                 => 'nullable|string|max:255|unique:events,slug',
            'description'          => 'nullable|string',
            'cover_image'          => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'starts_at'            => 'required|date',
            'ends_at'              => 'nullable|date|after_or_equal:starts_at',
            'location_type'        => 'required|in:physical,online,hybrid',
            'location_address'     => 'nullable|string',
            'location_link'        => 'nullable|url',
            'platform'             => 'nullable|string|max:255',
            'capacity'             => 'nullable|integer|min:1',
            'registration_required'=> 'boolean',
            'requires_payment'     => 'boolean',
            'price_amount'         => 'nullable|integer|min:0',
            'price_currency'       => 'nullable|string|max:10',
            'registration_deadline'=> 'nullable|date',
            'status'               => 'required|in:draft,published,cancelled,finished',
            'categories'           => 'nullable|array',
            'categories.*'         => 'exists:event_categories,id',
            'speakers'             => 'nullable|array',
            'speakers.*.user_id'   => 'required|exists:users,id',
            'speakers.*.title'     => 'nullable|string|max:255',
            'speakers.*.bio'       => 'nullable|string',
            'speakers.*.order'     => 'nullable|integer|min:0',
            'sessions'             => 'nullable|array',
            'sessions.*.title'     => 'required|string|max:255',
            'sessions.*.time_string' => 'required|string|max:255',
            'sessions.*.location'  => 'nullable|string|max:255',
            'sessions.*.duration'  => 'nullable|string|max:100',
            'sessions.*.description' => 'nullable|string',
            'sessions.*.order'     => 'nullable|integer|min:0',
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Event::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('event-covers', 'public');
        }

        $event = Event::create([
            'slug'                 => $slug,
            'title'                => $validated['title'],
            'description'          => $validated['description'] ?? null,
            'cover_image_path'     => $coverPath,
            'starts_at'            => $validated['starts_at'],
            'ends_at'              => $validated['ends_at'] ?? null,
            'location_type'        => $validated['location_type'],
            'location_address'     => $validated['location_address'] ?? null,
            'location_link'        => $validated['location_link'] ?? null,
            'platform'             => $validated['platform'] ?? null,
            'capacity'             => $validated['capacity'] ?? null,
            'registration_required'=> $validated['registration_required'] ?? false,
            'requires_payment'     => $validated['requires_payment'] ?? false,
            'price_amount'         => $validated['price_amount'] ?? 0,
            'price_currency'       => $validated['price_currency'] ?? 'IRT',
            'registration_deadline'=> $validated['registration_deadline'] ?? null,
            'status'               => $validated['status'],
            'created_by'           => auth()->id(),
            'views_count'          => 0,
        ]);

        // Sync categories
        $event->categories()->sync($validated['categories'] ?? []);

        // Create speakers
        if (!empty($validated['speakers'])) {
            foreach ($validated['speakers'] as $speaker) {
                EventSpeaker::create([
                    'event_id' => $event->id,
                    'user_id'  => $speaker['user_id'],
                    'title'    => $speaker['title'] ?? null,
                    'bio'      => $speaker['bio'] ?? null,
                    'order'    => $speaker['order'] ?? 0,
                ]);
            }
        }

        // Create sessions
        if (!empty($validated['sessions'])) {
            foreach ($validated['sessions'] as $session) {
                EventSession::create([
                    'event_id'     => $event->id,
                    'title'        => $session['title'],
                    'time_string'  => $session['time_string'],
                    'location'     => $session['location'] ?? null,
                    'duration'     => $session['duration'] ?? null,
                    'description'  => $session['description'] ?? null,
                    'order'        => $session['order'] ?? 0,
                ]);
            }
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'رویداد با موفقیت ایجاد شد.');
    }

    public function edit(Event $event)
    {
        $event->load(['categories', 'speakers.user', 'sessions']);
        $users = User::orderBy('name')->get();
        $categories = EventCategory::where('is_active', true)->get();
        return view('admin.events.edit', compact('event', 'users', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title'                => 'required|string|max:255',
            'slug'                 => 'nullable|string|max:255|unique:events,slug,' . $event->id,
            'description'          => 'nullable|string',
            'cover_image'          => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'starts_at'            => 'required|date',
            'ends_at'              => 'nullable|date|after_or_equal:starts_at',
            'location_type'        => 'required|in:physical,online,hybrid',
            'location_address'     => 'nullable|string',
            'location_link'        => 'nullable|url',
            'platform'             => 'nullable|string|max:255',
            'capacity'             => 'nullable|integer|min:1',
            'registration_required'=> 'boolean',
            'requires_payment'     => 'boolean',
            'price_amount'         => 'nullable|integer|min:0',
            'price_currency'       => 'nullable|string|max:10',
            'registration_deadline'=> 'nullable|date',
            'status'               => 'required|in:draft,published,cancelled,finished',
            'categories'           => 'nullable|array',
            'categories.*'         => 'exists:event_categories,id',
            'speakers'             => 'nullable|array',
            'speakers.*.user_id'   => 'required|exists:users,id',
            'speakers.*.title'     => 'nullable|string|max:255',
            'speakers.*.bio'       => 'nullable|string',
            'speakers.*.order'     => 'nullable|integer|min:0',
            'sessions'             => 'nullable|array',
            'sessions.*.title'     => 'required|string|max:255',
            'sessions.*.time_string' => 'required|string|max:255',
            'sessions.*.location'  => 'nullable|string|max:255',
            'sessions.*.duration'  => 'nullable|string|max:100',
            'sessions.*.description' => 'nullable|string',
            'sessions.*.order'     => 'nullable|integer|min:0',
        ]);

        $slug = $validated['slug'] ?? Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $coverPath = $event->cover_image_path;
        if ($request->hasFile('cover_image')) {
            if ($coverPath && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }
            $coverPath = $request->file('cover_image')->store('event-covers', 'public');
        }

        $event->update([
            'slug'                 => $slug,
            'title'                => $validated['title'],
            'description'          => $validated['description'] ?? null,
            'cover_image_path'     => $coverPath,
            'starts_at'            => $validated['starts_at'],
            'ends_at'              => $validated['ends_at'] ?? null,
            'location_type'        => $validated['location_type'],
            'location_address'     => $validated['location_address'] ?? null,
            'location_link'        => $validated['location_link'] ?? null,
            'platform'             => $validated['platform'] ?? null,
            'capacity'             => $validated['capacity'] ?? null,
            'registration_required'=> $validated['registration_required'] ?? false,
            'requires_payment'     => $validated['requires_payment'] ?? false,
            'price_amount'         => $validated['price_amount'] ?? 0,
            'price_currency'       => $validated['price_currency'] ?? 'IRT',
            'registration_deadline'=> $validated['registration_deadline'] ?? null,
            'status'               => $validated['status'],
        ]);

        // Sync categories
        $event->categories()->sync($validated['categories'] ?? []);

        // Delete old speakers and recreate
        $event->speakers()->delete();
        if (!empty($validated['speakers'])) {
            foreach ($validated['speakers'] as $speaker) {
                EventSpeaker::create([
                    'event_id' => $event->id,
                    'user_id'  => $speaker['user_id'],
                    'title'    => $speaker['title'] ?? null,
                    'bio'      => $speaker['bio'] ?? null,
                    'order'    => $speaker['order'] ?? 0,
                ]);
            }
        }

        // Delete old sessions and recreate
        $event->sessions()->delete();
        if (!empty($validated['sessions'])) {
            foreach ($validated['sessions'] as $session) {
                EventSession::create([
                    'event_id'     => $event->id,
                    'title'        => $session['title'],
                    'time_string'  => $session['time_string'],
                    'location'     => $session['location'] ?? null,
                    'duration'     => $session['duration'] ?? null,
                    'description'  => $session['description'] ?? null,
                    'order'        => $session['order'] ?? 0,
                ]);
            }
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'رویداد با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Event $event)
    {
        $cover = $event->cover_image_path;

        DB::transaction(function () use ($event) {
            $event->categories()->detach();
            $event->speakers()->delete();
            $event->sessions()->delete();
            $event->delete();
        });

        if ($cover && Storage::disk('public')->exists($cover)) {
            Storage::disk('public')->delete($cover);
        }

        return redirect()->route('admin.events.index')
            ->with('success', 'رویداد با موفقیت حذف شد.');
    }
}