<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Tweet;

class AdminController extends Controller
{
   // public function __construct()
    //{
    //    $this->middleware('admin');
   // }


    public function getUsers(User $user){
        $all_user = $user->getAllUsers(auth()->user()->id);

	      return $all_user;
    }

    public function userDel(User $user,$user_id){
        //ユーザ削除
	      $user->userdelete($user_id);
	      return back();
    }

    public function tweetDel(Tweet $tweet,$tweet_id){
       $tweet->tweetdelete($tweet_id);
    }

}
