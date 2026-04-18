<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return (new ProfileResource($user));
    }
    public function update(Request $request)
    { {
            $user = auth()->user();
            $validateUser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'nullable|max:10',
                'address' => 'required',
                'phone' => 'required|max:20',
                'profile_img' => 'nullable|mimes:png,jpg,jpeg,gif'
            ]);
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation Error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if ($request->hasFile('profile_img')) {
                $path = public_path('/uploads/');

                if ($user->profile_img && file_exists($path . $user->profile_img)) {
                    unlink($path . $user->profile_img);
                }
                $img = $request->file('profile_img');
                $ext = $img->getClientOriginalExtension();
                $imageName = time() . '.' . $ext;
                $img->move($path, $imageName);
            } else {
                $imageName = $user->profile_img;
            }
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password ? $request->password : $user->password,
                'address' => $request->address,
                'phone' => $request->phone,
                'profile_img' => $imageName,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Profile Updated Successfully',
                'data' => new ProfileResource($user)
            ]);
        }
    }
}

