<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signUp(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'address' => 'required',
                'phone' => 'required|max:20',
                'profile_img' => 'required|mimes:png,jpg,jpeg,gif'
            ]
        );
        if ($validateUser->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'validation error',
                    'error' => $validateUser->errors()->all()
                ],
                401
            );
        }
        $img = $request->profile_img;
        $ext = $img->getClientOriginalExtension();
        $imageName = time() . '.' . $ext;
        $img->move(public_path() . '/uploads', $imageName);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'address' => $request->address,
            'phone' => $request->phone,
            'profile_img' => $imageName,
        ]);
        return response()->json(
            [
                'status' => true,
                'message' => 'user created successfully',
                'user' => $user
            ],
            200
        );
    }
    public function login(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );
        if ($validateUser->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Authentication failed',
                    'error' => $validateUser->errors()->all()
                ],
                401
            );
        }
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user();
            return response()->json([

                'status' => true,
                'message' => 'User Logged in Successfully',
                'token' => $authUser->createToken('API Token')->plainTextToken,
                'token_type' => 'bearer'

            ], 200);

        } else {
            return response()->json([
                'status' => false,
                'message' => 'Email and Password does not match'
            ], 401);
        }
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        return response()->json([
            'status' => true,
            'user' => $user,
            'message' => 'User Logged Out Successfully',
        ], 200);
    }
}
