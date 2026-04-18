<?php

namespace App\Http\Controllers\api;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $post = Post::all();

        return response()->json([
            'status' => true,
            'message' => 'Post Data',
            'data' => PostResource::collection($post),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
            //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatePost = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'post_image' => 'required|mimes:png,jpg,jpeg,gif',

        ]);

        if ($validatePost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'error' => $validatePost->errors()->all()
            ], 401);
        }

        $img = $request->post_image;
        $ext = $img->getClientOriginalExtension();
        $postImage = time() . '.' . $ext;
        $img->move(public_path() . '/uploads/', $postImage);


        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'post_image' => $postImage,
            'user_id' => auth()->user()->id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post Created Successfully',
            'data' => new PostResource($post)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }


    public function update(Request $request, Post $post)
    {
        if ($request->user()->cannot('update', $post)) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorize to update this post.'
            ], 403);
        }

        $validatePost = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'post_image' => 'nullable|mimes:png,jpg,jpeg,gif',
        ]);

        if ($validatePost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'error' => $validatePost->errors()->all()
            ], 422);
        }

        $postImage = $post->post_image;

        if ($request->hasFile('post_image')) {
            $path = public_path('uploads/');

            if ($post->post_image && file_exists($path . $post->post_image)) {
                unlink($path . $post->post_image);
            }

            $img = $request->file('post_image');
            $imageName = time() . '.' . $img->getClientOriginalExtension();
            $img->move($path, $imageName);
            $postImage = $imageName;
        }

        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'post_image' => $postImage,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post Updated Successfully',
            'data' => new PostResource($post)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Request $request)
    {

        if ($request->user()->cannot('delete', $post)) {
            return response()->json([
                'status' => false,
                'message' => 'You are not authorize to delete this post.'
            ], 403);
        }
        if ($post->post_image) {
            $filePath = public_path('uploads/' . $post->post_image);

            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $post->delete();
        return response()->json([
            'status' => true,
            'message' => 'Post Deleted Successfully',
            'data' => new PostResource($post)
        ]);
    }


}

