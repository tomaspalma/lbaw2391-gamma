<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function show_personal() {
        return view('pages.homepage', [
            'feed' => 'personal'
        ]);
    }

    public function show_popular() {
        return view('pages.homepage', [
            'feed' => 'popular'
        ]);
    }
}
