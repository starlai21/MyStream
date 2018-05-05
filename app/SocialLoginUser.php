<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialLoginUser extends Model
{
    protected $table = 'social_login_users';

    protected $fillable = [
    	'provider','provider_user_id','user_id'
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }


}
