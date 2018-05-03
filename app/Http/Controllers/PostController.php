<?php

namespace App\Http\Controllers;

use App\Post;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function user(){
        return User::where('name',request('userName'))->first();
    }

    public function isAdmin($user){
        return Auth::user() && (Auth::user() == $user);
    }

    public function checkLogined(){
        if (!Auth::user())
            return response()
                    ->json(['status' => 'error',
                            'message' => 'Please try again.']);
    }

    public function checkAuthor($post){
        $this->checkLogined();
        if ($post->user != Auth::user())
            return response()
                    ->json(['status' => 'error',
                            'message' => 'Permission denied.']);
    }


    public function toggle(Post $post){
        if($post){
            $this->checkAuthor($post);
            $post['posted'] = !$post['posted'];
            $post->save();
            return response()
                        ->json(['status' => 'success',
                                'message' => 'Post status changed.']);
        }
        else{
            return response()
                        ->json(['status' => 'error',
                                'message' => 'No such post.']);
        }
    }

    public function index(){
        //sleep(1);
    	if (($postId = request()->input('postId')) != null){
            $post = Post::with(['user:name,id','tags:name'])
                            ->whereHas('user',function($q){
                                $q->where('name',request()->input('userName'));
                            })
                            ->findOrFail($postId);

            if (Auth::user() && Auth::user()->id == $post->user->id){
                $prev = $post->user->posts()
                                    ->where('id','<',$post->id)
                                    ->orderBy('id','desc')
                                    ->first();
                $next = $post->user->posts()
                                    ->where('id','>',$post->id)
                                    ->first();
                return ['prev' => $prev, 'cur' => $post, 'next' => $next];
            }
            else{
                if (!$post->posted){
                    return ['prev' => null, 'cur' => null, 'next' => null];
                }
                $prev = $post->user->posts()
                                    ->where('posted',true)
                                    ->where('id','<',$post->id)
                                    ->orderBy('id','desc')
                                    ->first();
                $next = $post->user->posts()
                                    ->where('posted',true)
                                    ->where('id','>',$post->id)
                                    ->first();
                return ['prev' => $prev, 'cur' => $post, 'next' => $next];
            }
            // in draft state.
            if (!$post->posted){
                if (Auth::user() && Auth::user()->id == $post->user->id)
                    return $post;
                return null;
            }
            return $post;
    	}
    	else{
            $posts = Post::latest();
            if ($request = request(['month','year','tag','userName'])){
                $posts->filter($request);
            }
            if (!$this->isAdmin($this->user()))
                $posts = $posts->where('posted',true);
            $pagination = request('paginate');
            if ($pagination == 'false'){
                return $posts->with('tags:name')->get();
            }
            else{
                return $posts->with('tags:name')->paginate($pagination);
            }

    	}
    }


    public function archives(){
        $user = $this->user();
        return Post::archives($user->name,$this->isAdmin($user));
    }

    public function tags(){
        // return Tag::whereHas('posts')
        //             ->withCount('posts')->get();
        //needs to be improved. 
        $user = $this->user();

       $query = DB::table('posts')
                    ->join('post_tag','posts.id','=','post_tag.post_id')
                    ->join('tags','post_tag.tag_id','=','tags.id')
                    ->select('tags.name')
                    ->distinct()
                    ->where('posts.user_id','=',$user->id);
        if (!$this->isAdmin($user))
            $query = $query->where('posted',true);

        return $query->get();
    }



    public function store(Request $request){

        $this->checkLogined();
        $params = $request->validate(['title' => 'required','abstract'=>'nullable','content'=>'required', 'posted' => 'required']);

        $tagNames = $request->validate(['tags' => 'nullable']);


        $params['user_id'] = Auth::user()->id;
        $post = Post::create($params);
        foreach ($tagNames['tags'] as $tagName){
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $post->tags()->attach($tag);
        }

        return response()
                    ->json(['status' => 'success',
                            'message' => 'Succeed to create the post.',
                            'post'    => Post::with('tags:name')->find($post->id)]);

    }

    public function update(Post $post,Request $request){
        if ($post){
            $this->checkAuthor($post);
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
            $this->checkAuthor($post);
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

    public function storeImages(){
        $res = [];
        foreach ($_FILES as $key => $value){
            $pos = str_replace('_','.',$key);
            $path = request()->file($key)->store('public/images');
            $res[$pos] = asset('/storage/'.substr($path,7));
        }
        return $res;
    }

}
