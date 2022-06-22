<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostVisit;
use App\Models\Reply;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index($id)
    {
        $post = Post::with('user')->where('id', $id)->firstOrFail();
        $comments = Reply::where('post_id', $id)->get();

        $visit = new PostVisit();
        $visit->user_id = Auth()->id() ?? 0;
        $visit->post_id = $id;
        $visit->ip = request()->ip();
        $visit->save();

        $resources = [
            'post' => $post,
            'comments' => $comments
        ];
        return view('post', $resources);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return $post;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

    public function actions(Request $request)
    {
        if ($request->type == 'addPost'){

            $post = new Post();
            $post->user_id = Auth()->id();
            $post->title = $request->postTitle;
            $post->text = $request->postText;
            $post->save();

            return $post->id;
        }

        if ($request->type == 'addComment'){

            $comment = new Reply();
            $comment->user_id = Auth()->id();
            $comment->post_id = $request->postId;
            $comment->reply = $request->comment;
            $comment->save();

            return $comment->id;
        }

        if ($request->type == 'deleteComment'){
            $comment = Reply::where('id', $request->commentId)->first();
            if ($comment->user_id == Auth()->id()){
                Reply::where('id', $request->commentId)->delete();
                return 'success';
            } else {
                return 'noAccess';
            }
        }

        if ($request->type == 'deletePost'){
            $post = Post::where('id', $request->postId)->first();
            if ($post->user_id == Auth()->id()){
                Reply::where('post_id', $request->postId)->delete();
                Post::where('id', $request->postId)->delete();
                return 'success';
            } else {
                return 'noAccess';
            }
        }
    }
}
