<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    
	protected $fillable = ['title','content','abstract','user_id','posted'];
    protected $hidden = ['user_id'];

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
        if (isset($filters['userName'])){
            $query->whereHas('user',function($q) use($filters){
                $q->where('name',$filters['userName']);
            });
        }


    }
    public static function archives($userName,$isAdmin){
    	// mysql
        // return static::selectRaw('year(created_at) year, monthname(created_at) month,count(*) published')
        //     ->groupBy('year','month')
        //     ->orderByRaw('min(created_at) desc')
        //     ->get()
        //     ->toArray();

        //sqlite
        $query = static::selectRaw("strftime('%Y',created_at) year, 
        						strftime('%m',created_at) month,
        						count(*) published")
                        ->whereHas('user', function($q) use($userName){
                            $q->where('name',$userName);
                        });
        if (!$isAdmin)
            $query = $query->where('posted',true);

        return  $query->groupBy('year','month')
        			    ->orderByRaw('min(created_at) desc')
        				->get()
        				->toArray();
    }


    public function tags(){
    	return $this->belongsToMany(Tag::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
