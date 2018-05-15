<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function checkLogined(){
        if (!Auth::user())
            return ['status' => 'error',
                    'message' => 'Please try again.'];
    }

    //
    public function store(){

    }

    public function update(){
    	$this->checkLogined();
    	$params = request()->validate(['name' => 'required',
    									'color' => 'required',
    									'pagination' => 'required',
    									'slogan' => 'nullable']);
  
    	Auth::user()->blog()->update($params);
    	return ['status' => 'success','message' => 'Blog settings saved.'];
    }

    public function index(){
		$userName = request()->input('userName');
		if (!User::exists($userName) || !User::activated($userName)) {
			abort(404);
	    }
	    $user = User::where('name',$userName)->with('blog')->first();
	    return ['status' => 'success',
                'user' => $user];
    }
}
