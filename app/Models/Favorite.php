<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
   protected $fillable = ['id', 'user_id', 'item_id'];

   public $timestamps = false;


   //イイネしているかどうかの判定
   public function isFavorite(Int $user_id, Int $item_id)
   {
       return (boolean) $this->where('user_id', $user_id)->where('item_id', $item_id)->first();
   }

   //favoriteテーブルのuser_idとitem_idカラムに追加する。
   //user_idがイイネした人で、item_idがイイネ対象の投稿のid
   public function storeFavorite(Int $user_id, Int $item_id)
   {
       $this->user_id = $user_id;
       $this->item_id = $item_id;
       $this->save();

       return;
   }
   
   //いいねのidを探してそいつを削除
   public function destroyFavorite(Int $item_id, Int $user_id)
   {
      return $this->where('item_id', $item_id)->where('user_id', $user_id)->delete();
   }
   

}
