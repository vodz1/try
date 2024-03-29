<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class PostController extends Controller
{
    public function index()
    {
        \App\Models\User::class::factory(10)->create();
        $posts = Post::with('user')->paginate(10); // Fetch paginated posts from the database
        return view('posts.index', ['posts' => $posts]); // Pass the $posts variable to the view
    }

    public function create()
    {
        return view('posts.create');
    }


    public function store(Request $request)
    {
        // dd($request);
        // $request->validate([
        //     'title' => 'required|max:255',
        //     'body' => 'required',
        //     'user_id' => 'required'
        // ]);
        Post::create(['title' => $request->title, 'body' => $request->body, 'enabled' => 1, 'published_at' => Carbon::now(), 'user_id' => 2]);

        return redirect()->route('posts.index');
    }
    public function show(string $id)
    {
        $post = Post::find($id);
        return view('posts.show', ['post' => $post]);
    }


    public function edit($id)
    {
        $post = Post::find($id);
        return view('posts.edit', ['post' => $post]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);
        Post::find($id)->update(['title' => $request->title, 'body' => $request->body]);
        return redirect()->route('posts.index');
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        return redirect()->route('posts.index');
    }
    public function trash()
    {
        $dPosts = Post::onlyTrashed()->get();
        return view('posts.trash', ['dPosts' => $dPosts]);
    }
}
