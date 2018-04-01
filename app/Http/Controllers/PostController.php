<?php

namespace App\Http\Controllers;

use App\Post;
use App\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //
    public function store(){

    }

    public function index($postId = null){
        sleep(1);
    	if ($postId != null){
    		return Post::with('tags')->find($postId);
    	}
    	else{
            $posts = Post::latest();
            if ($request = request(['month','year','tag'])){
                $posts->filter($request);
            }
    		return $posts->with('tags')->paginate(6);
    	}
    }

    public function admin_index(){
        // Post::all()
        return view('admin.posts',['posts' => Post::all()]);
    }

    public function archives(){
        return Post::archives();
    }

    public function tags(){
        return Tag::has('posts')->withCount('posts')->get();
    }
}
