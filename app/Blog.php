<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{

	protected $fillable = [
        'user_id','name','color','slogan','pagination'
    ];

    protected $hidden = [
        'user_id'
    ];


    //
    public function user(){
    	return $this->belongsTo(User::class);
    }
}
