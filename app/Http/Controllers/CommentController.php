<?php

namespace App\Http\Controllers;

use App\Comment;
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
}
