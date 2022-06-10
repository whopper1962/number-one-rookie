<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Drink;
use App\Models\Tweet;

class DrinkController extends Controller
{
    public function store($tweetId, Request $request, Drink $drink)
    {
        $user = auth()->user();
	      $user_id = $user->id;
	      $is_favorite = $drink->isfavorite($user_id, $tweetId);

	      if(!$is_favorite){
	         $drink->storeFavorite($user_id,$tweetId);
	      }
    }

    public function destroy($tweetId, Drink $drink)
    {
        $user = auth()->user();
	      $user_id = $user->id;

	      //$favorite_id = $drink->id;
	      $is_favorite = $drink->isFavorite($user_id,$tweetId);

	      if($is_favorite){
	          $drink->destroyFavorite($tweetId, $user_id);
	      }
    }

    public function myPage()
    {//Drinkテーブルから、自分がイイネした投稿のidだけを取り出す　
       $myDrink = Drink::where('user_id',auth()->user()->id)->get();
       
       $myDrinkArray = [];

       foreach($myDrink as $i => $item){
	        $drink = Tweet::with('image')->where('id', $item->tweet_id)->get();
		      
		      array_push($myDrinkArray, $drink);
       }
       return $myDrinkArray;
    }
}
