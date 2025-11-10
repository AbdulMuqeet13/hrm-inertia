<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PublicPageController extends Controller
{
    public function home()
    {
        return Inertia::render('Public/Home');
    }

    public function about()
    {
        return Inertia::render('Public/About');
    }

    public function careers()
    {
        return Inertia::render('Public/Careers');
    }

    public function contact()
    {
        return Inertia::render('Public/Contact');
    }

    public function policy()
    {
        return Inertia::render('Public/Policy');
    }
}
