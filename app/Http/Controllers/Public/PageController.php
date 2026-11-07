<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class PageController extends Controller
{
    public function about()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('public.pages.about', compact('settings'));
    }

    public function contact()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('public.pages.contact', compact('settings'));
    }
}