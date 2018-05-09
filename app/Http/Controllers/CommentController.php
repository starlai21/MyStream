<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Like;
use App\Post;
use App\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function checkLogined(){
        if (!Auth::user())
            return response()
                    ->json(['status' => 'error',
                            'message' => 'Please try again.']);
    }
    public function index(){
        $post = Post::find(request()->input('post_id'));
        $comments = $post->comments()
                            ->with(['replies' => function ($q){
                                $q->with('user:name,id')
                                    ->withCount('likes');
                            }])
                            ->with('user:name,id')
                            ->withCount('likes')
                            ->withCount('replies')
                            ->paginate(8);
        $likes = [];
        $user = Auth::user();
        foreach($comments as $comment){
            if ($user){
                if ($comment->likes()
                            ->where('user_id',$user->id)
                            ->exists())
                    array_push($likes,true);
                else
                    array_push($likes,false);
            }
            else{
                array_push($likes,false);
            }

            foreach($comment->replies as $reply){
                if ($user){
                    if ($reply->likes()
                                ->where('user_id',$user->id)
                                ->exists())
                        $reply->like = true;
                    else
                        $reply->like = false;
                }
                else{
                    $reply->like = false;
                }
            }
        }




        return ['comments' => $comments , 'likes' => $likes];

    }



    public function store(){
    	$this->checkLogined();
    	$params = request()->validate(['post_id' => 'required','content'=>'required']);
    	$user = Auth::user();
    	$params['user_id'] = $user->id;
    	$comment = Comment::create($params);
    	$comment = Comment::with('user:name,id')
    						->withCount('likes')
    						->withCount('replies')
    						->find($comment->id);
    	return ['status' => 'success',
    			'message' => 'Your comment has been successfully posted!',
    			'comment' => $comment];
    }

    public function storeReply(){
        $this->checkLogined();
        $params = request()->validate(['comment_id' => 'required','content'=>'required']);
        $user = Auth::user();
        $params['user_id'] = $user->id;
        $reply = Reply::create($params);
        $reply = Reply::with('user:name,id')
                        ->withCount('likes')
                        ->find($reply->id);
        return ['status' => 'success',
                'message' => 'Your reply has been successfully posted!',
                'reply' => $reply];
    }


    public function toggleLike(){
        $this->checkLogined();
        if (request()->input('comment_id') != ''){
            $type = "App\Comment";
            $id = request()->input('comment_id');
        }
        else{
            $type = "App\Reply";
            $id = request()->input('reply_id');
        }
        $user = Auth::user();
        $like = Like::where('user_id',$user->id)
                        ->where('likable_id',$id)
                        ->where('likable_type',$type)
                        ->first();
        if ($like){
            $like->delete();
        }
        else{
            Like::create(['user_id' => $user->id,
                            'likable_id' => $id,
                            'likable_type' => $type]);
        }
        return ['status' => 'success'];
    }

}
