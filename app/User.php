<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'email', 'password','activation_code', 'activated','password_reset_code'
    // ];
    protected $fillable = [
        'name','avatar_url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     'password'
    // ];

    public function routeNotificationForMail($notification)
    {
        return $this->emailLoginUser->email;
    }



    public static function exists($userName){
        return static::where('name', $userName)->exists();
    }

    public static function activated($userName){
        $user = static::where('name', $userName)->first();
        if ($user->emailLoginUser)
            return $user->emailLoginUser->activated;
        return true;
    }




    public function posts(){
        return $this->hasMany(Post::class);
    }


    public function emailLoginUser(){
        return $this->hasOne(EmailLoginUser::class);
    }

    public function socialLoginUser(){
        return $this->hasMany(SocialLoginUser::class);
    }


    public function blog(){
        return $this->hasOne(Blog::class);
    }


    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function replies(){
        return $this->hasMany(Reply::class);
    }

    public function likes(){
        return $this->hasMany(Like::class);
    }



    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

}
