<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    
	protected $fillable = ['title','content','abstract','user_id'];


 //    public function user(){
 //    	return $this->belongsTo(User::class);
 //    }
 //    public function comments(){
 //    	return $this->hasMany(Comment::class);
 //    }

    public function scopeFilter($query,$filters){

        if (isset($filters['month'])){
            $query->whereMonth('created_at',$filters['month']);
        }

        if (isset($filters['year'])){
            $query->whereYear('created_at',$filters['year']);
        }

        if (isset($filters['tag'])){
            $query->whereHas('tags',function($q) use($filters){
                $q->where('name',$filters['tag']);
            });
        }


    }
    public static function archives(){
    	// mysql
        // return static::selectRaw('year(created_at) year, monthname(created_at) month,count(*) published')
        //     ->groupBy('year','month')
        //     ->orderByRaw('min(created_at) desc')
        //     ->get()
        //     ->toArray();

        //sqlite
        return static::selectRaw("strftime('%Y',created_at) year, 
        						strftime('%m',created_at) month,
        						count(*) published")
        				->groupBy('year','month')
        				->orderByRaw('min(created_at) desc')
        				->get()
        				->toArray();
    }


    public function tags(){
    	return $this->belongsToMany(Tag::class);
    }
}
