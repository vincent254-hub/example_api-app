<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Post $post)
    {

        $user = Auth::user();
        $existingLike = $post->likes()->where('user_id', $user-id)->first();
        $like = $post->likes()->create([]);

        if($existingLike) {
            $existingLike ->delete();
            return response()->json(null, 204);
        }else {
            $like = $post->likes()->create([
                'user_id'=> $user->id
            ]);

            return response()->json($like, 201);
        }

        return response()->json($like, 201);
    }

    public function destroy(Post $post)
    {
        $post->likes()->delete();

        return response()->json(null, 204);
    }
}