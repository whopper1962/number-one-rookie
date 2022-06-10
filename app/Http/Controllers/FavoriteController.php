<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;


class FavoriteController extends Controller
{
    public function store($itemId, Request $request, Favorite $favorite)
    {
        $user = auth()->user();//ログイン機能ができたら
        $user_id = $user->id;
	      $is_favorite = $favorite->isFavorite($user_id, $itemId);
        if(!$is_favorite){
	          $favorite->storeFavorite($user_id,$itemId);
	      }
    }

    public function destroy($itemId, Favorite $favorite)
    {
        //$user_id = $favorite->user_id;
        $user = auth()->user();//ログイン機能ができたら
        $user_id = $user->id;

	      $favorite_id = $favorite->id;
	      $is_favorite = $favorite->isFavorite($user_id,$itemId);

	      if($is_favorite){
	         $favorite->destroyFavorite($itemId, $user_id);
	      }
    }

    public function showFavorite($item_id)
    {
        $user = auth()->user();
        $user_id = $user->id;
        $favorite_item = Favorite::where('item_id',$item_id)->where('user_id',$user_id)->first();
	      
	       //$setFavorite = isset($favorite_item);
		      return $favorite_item;
    }
}
