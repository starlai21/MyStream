<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailLoginUser extends Model
{
    
    protected $table = 'email_login_users';

    protected $fillable = [
    	'email', 'password','activation_code', 'activated','password_reset_code','user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }
}
