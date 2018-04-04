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
    		return Post::with('tags:name')->find($postId);
    	}
    	else{
            $posts = Post::latest();
            if ($request = request(['month','year','tag'])){
                $posts->filter($request);
            }
    		return $posts->with('tags:name')->paginate(6);
    	}
    }


    public function archives(){
        return Post::archives();
    }

    public function tags(){
        return Tag::has('posts')->withCount('posts')->get();
    }



    public function store(Request $request){
        $params = $request->validate(['title' => 'required','abstract'=>'nullable','content'=>'required']);
        $tagNames = $request->validate(['tags' => 'nullable']);
        $post = Post::create($params);
        foreach ($tagNames['tags'] as $tagName){
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $post->tags()->attach($tag);
        }

        return response()
                    ->json(['status' => 'success',
                            'message' => 'Succeed to create the post.']);

    }

    public function update(Post $post,Request $request){
        if ($post){
            $params = $request->validate(['title' => 'required','abstract'=>'nullable','content'=>'required']);
            $tagNames = $request->validate(['tags' => 'nullable']);

            $post->update($params);
            $post->tags()->detach();

            foreach ($tagNames['tags'] as $tagName){

                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $post->tags()->attach($tag);
            }
            return response()
                        ->json(['status' => 'success',
                                'message' => 'Succeed to update the post.']);


        }
        else{
            return response()
                        ->json(['status' => 'error',
                                'message' => 'Post update failure.']);
        }
    }

    public function delete(Post $post){
        if($post){
            $post->delete();
            return response()
                        ->json(['status' => 'success',
                                'message' => 'Succeed to delete the post.']);
        }
        else{
            return response()
                        ->json(['status' => 'error',
                                'message' => 'Post delete failure.']);
        }
    }


}
