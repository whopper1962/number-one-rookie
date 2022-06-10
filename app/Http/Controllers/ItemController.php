<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\User;
use App\Models\Favorite;


class ItemController extends Controller
{
    public function index(Item $item)
    {//この処理は使っていない。cronに持たせている。
	      $client = new \GuzzleHttp\Client();
	      $url = 'https://product.starbucks.co.jp/api/category-product-list/beverage/index.json';
	      $response = $client->request(
	          'GET',
		        $url
	      );
	      $api =  json_decode((string)$response->getBody());
        //apiには配列ですべてのスタバのメニュリストが入っていて、このときにでコードして配列にしている。

	      foreach($api as $menuitem){
	          $unique = $item->where('product_code', '=', $menuitem->product_code)->first();
		        if(!$unique){//空じゃなければ
			          $item->create([
				        'product_code' => $menuitem->product_code,
					      'product_name' => $menuitem->product_name,
					      'category' => $menuitem->category2_list_path,
                'item_img' => $menuitem->image1,
		            'product_note' => $menuitem->product_note,
			          'catchcopy' => $menuitem->catchcopy,
			          'price' => $menuitem->price,
				        'sales_day' => $menuitem->sales_day,
				       ]);
			      }
	      }
	      //return $item->get();//更新処理をわけたいので
    }

    public function getItems(Item $item, Favorite $favorite, User $user)
    {
       if(auth()->user()){
	       $favoriteItems = Item::with(['favorite' => function($q){
	          $q->where('user_id', auth()->user()->id);
	       }])->orderBy('sales_day','desc')->get();
	      }else{
	        $favoriteItems = Item::orderBy('sales_day','desc')->get();
	      }

	     foreach ($favoriteItems as $item_data) {
	       $count = $favorite->where('item_id',$item_data->id)->get();
	       $item_data->favorite_count = $count->count();
	     }
	     return $favoriteItems;
    }
    
    public function getPriceDescItems(Item $item, Favorite $favorite, User $user)
    {//値段降順のソート
       if(auth()->user()){
	       $priceDesc = Item::with(['favorite' => function($q){
	          $q->where('user_id', auth()->user()->id);
	       }])->orderBy('price','desc')->get();
	     }else{
	       $priceDesc = Item::orderBy('price','desc')->get();
	     }
	     
	     foreach ($priceDesc as $item_data) {
	       $count = $favorite->where('item_id',$item_data->id)->get();
	       $item_data->favorite_count = $count->count();
	     }
	     return $priceDesc;
    }
    
    public function getPriceAscItems(Item $item)
    {//値段昇順のソート
       if(auth()->user()){
	       $priceAsc = Item::with(['favorite' => function($q){
	          $q->where('user_id', auth()->user()->id);
	       }])->orderBy('price','asc')->get();
	     }else{
	        $priceAsc = Item::orderBy('price','asc')->get();
	     }
	     foreach ($priceAsc as $item_data) {
	       $count = Favorite::where('item_id',$item_data->id)->get();
	       $item_data->favorite_count = $count->count();
	     }
	     return $priceAsc;
    }
    
    public function getCategoryItems(Item $item,$category)
    {//カテゴリ別の出力
       if(auth()->user()){
	       $categoryget = Item::with(['favorite' => function($q){
	          $q->where('user_id', auth()->user()->id);
	       }])->where('category',$category)->get();
	     }else{
	       $categoryget = Item::where('category',$category)->get();
	     }
	     foreach ($categoryget as $item_data) {
	       $count = Favorite::where('item_id',$item_data->id)->get();
	       $item_data->favorite_count = $count->count();
	     }
	     return $categoryget;
	  }
    
    public function getMyFavoriteItems(){
      return Item::with(['favorite' => function($q){
         $q->where('user_id', auth()->user()->id);
      }])->get();
    }
} 
