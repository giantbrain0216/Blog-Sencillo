<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        return User::all()
            ->toArray();
    }
    public function show($id)
    {
        $user = User::all()->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user with id ' . $id . ' cannot be found'
            ], 400);
        }

        return $user;
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'phone_number' => 'required',
            'user_type' => 'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->phone_number = $request->phone_number;
        $user->user_type = $request->user_type;

        if (User::all()->save($user))
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user could not be added'
            ], 500);
    }

    public function update(Request $request, $id)
    {
        $user = User::all()->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user with id ' . $id . ' cannot be found'
            ], 400);
        }

        $updated = $user->fill($request->all())
            ->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user could not be updated'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::all()->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user with id ' . $id . ' cannot be found'
            ], 400);
        }

        if ($user->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'user could not be deleted'
            ], 500);
        }
    }
}
