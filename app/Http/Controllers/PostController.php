<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    public function index()
    {
        return $this->user
            ->posts()
            ->get(['title', 'slug', 'short_text', 'long_text', 'image_url'])
            ->toArray();
    }
    public function show($id)
    {
        $post = $this->user->posts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, post with id ' . $id . ' cannot be found'
            ], 400);
        }

        return $post;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'slug' => 'required',
            'short_text' => 'required',
            'long_text' => 'required',
            'image_url' => 'required',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->short_text = $request->short_text;
        $post->long_text = $request->long_text;
        $post->image_url = $request->image_url;

        if ($this->user->posts()->save($post))
            return response()->json([
                'success' => true,
                'post' => $post
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, post could not be added'
            ], 500);
    }

    public function update(Request $request, $id)
    {
        $post = $this->user->posts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, post with id ' . $id . ' cannot be found'
            ], 400);
        }

        $updated = $post->fill($request->all())
            ->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, post could not be updated'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $post = $this->user->posts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, post with id ' . $id . ' cannot be found'
            ], 400);
        }

        if ($post->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'post could not be deleted'
            ], 500);
        }
    }
}
