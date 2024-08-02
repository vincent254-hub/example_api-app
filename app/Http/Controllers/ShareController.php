<?php

namespace App\Http\Controllers;

use App\Models\Share;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'platform' => 'required|string',
        ]);

        $share = $post->shares()->create([
            'platform' => $request->platform,
            'user_id' => Auth::id(),
        ]);

        return response()->json($share, 201);
    }
}