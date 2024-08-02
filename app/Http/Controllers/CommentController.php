<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Post $post)
    {
        return $post->comments;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = $post->comments()->create([
            'content'=>$request->content,
            'user_id'=>Auth::id(),
        ]);

        return response()->json($comment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(sComment $comment)
    {
        return $comment;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {

        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'string',
        ]);

        $comment->update($request->all());

        return response()->json($comment, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

       return response()->json(null, 204);
    }
}
