<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Enums\EventStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventRegistrationController extends Controller
{
    public function store(Request $request, Event $event)
    {
        if (auth()->user()->isBlocked()) {
            return back()->with('error', 'حساب کاربری شما مسدود است و امکان انجام این عملیات را ندارید.');
        }

        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'برای ثبت‌نام باید وارد حساب کاربری خود شوید.');
        }

        try {
            DB::transaction(function () use ($event) {
                // Lock the event row to prevent race conditions
                $lockedEvent = Event::where('id', $event->id)->lockForUpdate()->firstOrFail();

                // Check 1: Event status must be Published
                if ($lockedEvent->status !== EventStatus::Published) {
                    throw new \Exception('این رویداد در وضعیت منتشر شده نیست و ثبت‌نام در آن امکان‌پذیر نیست.');
                }

                // Check 2: Registration is required
                if (!$lockedEvent->registration_required) {
                    throw new \Exception('برای این رویداد نیازی به ثبت‌نام نیست.');
                }

                // Check 3: Registration deadline not passed
                if ($lockedEvent->registration_deadline && $lockedEvent->registration_deadline->isPast()) {
                    throw new \Exception('مهلت ثبت‌نام برای این رویداد به پایان رسیده است.');
                }

                // Check 4: Capacity not full
                if ($lockedEvent->capacity) {
                    $currentCount = $lockedEvent->registrations()->count();
                    if ($currentCount >= $lockedEvent->capacity) {
                        throw new \Exception('ظرفیت این رویداد تکمیل شده است.');
                    }
                }

                // Check 5: User not already registered
                if ($lockedEvent->registrations()->where('user_id', auth()->id())->exists()) {
                    throw new \Exception('شما قبلاً در این رویداد ثبت‌نام کرده‌ید.');
                }

                // Create registration
                EventRegistration::create([
                    'event_id'       => $lockedEvent->id,
                    'user_id'        => auth()->id(),
                    'status'         => 'confirmed',
                    'payment_status' => 'not_required',
                    'registered_at'  => now(),
                ]);
            });

            return redirect()->route('public.events.show', $event->slug)
                ->with('success', 'ثبت‌نام شما با موفقیت انجام شد.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}