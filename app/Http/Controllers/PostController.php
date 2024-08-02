<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::with(['user','comments', 'likes', 'shares'])->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]); 

        $imagePath = null;
        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('images', 'public');
        }
        
        // $post = Auth::user()->posts()->create($request->all());
        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        return response()->json($post, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return Post::with(['user', 'comments', 'likes', 'shares'])->findOrfail($id);
        // return $post->load(['comments','likes','shares']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // $post->update($request->all());
        $post = Post::findOrfail($id);
        $post -> update($request->all());

        return response()->json($post, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $this->authorize('delete', $post);
        
        Post::findOrfail($id)->delete();
        

        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function like($id)
    {
        $post = Post::findOrfail($id);
        $like = $post->likes()->where('user_id', Auth::id())->first();

        if($like) {
            $like->delete();
            return response()->json(['message'=> 'Post unliked']);

        }else{
            $post ->likes()->create(['user_id' => Auth::id()]);
            return response()->json(['message' => 'post Liked']);
        }
    }
}
