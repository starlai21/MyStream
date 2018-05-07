<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id','post_id','content'
    ];





    public function user(){
    	return $this->belongsTo(User::class);
    }

    public function post(){
    	return $this->belongsTo(Post::class);
    }

    public function likes(){
    	return $this->morphMany(Like::class, 'likable');
    }

    public function replies(){
    	return $this->hasMany(Reply::class);
    }
}
