<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $user;
    protected $post;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    public function index()
    {
        return $this->user
            ->posts()
            ->categories()
            ->get(['name'])
            ->toArray();
    }
    public function show($id)
    {
        $category = $this->user->posts()->categories()->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category with id ' . $id . ' cannot be found'
            ], 400);
        }

        return $category;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $category = new Category();
        $category->name = $request->name;

        if ($this->user->posts()->categories()->save($category))
            return response()->json([
                'success' => true,
                'category' => $category
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category could not be added'
            ], 500);
    }

    public function update(Request $request, $id)
    {
        $category = $this->user->posts()->categories()->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category with id ' . $id . ' cannot be found'
            ], 400);
        }

        $updated = $category->fill($request->all())
            ->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category could not be updated'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $category = $this->user->posts()->categories()->find($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, category with id ' . $id . ' cannot be found'
            ], 400);
        }

        if ($category->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'category could not be deleted'
            ], 500);
        }
    }
}
