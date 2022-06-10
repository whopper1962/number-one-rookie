<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('getitems', 'ItemController@index');

Route::get('getitems/price/desc', 'ItemController@getPriceDescItems');
Route::get('getitems/price/asc', 'ItemController@getPriceAscItems');
Route::get('getitems/category/{category}', 'ItemController@getCategoryItems');
Route::get('getitems/myfavorite', 'ItemController@getMyFavoriteItems');

Route::get('itemsview', 'ItemController@getItems');

Route::get('tweets/tweet/{item_id}', 'TweetController@index');

Route::get('tweets/custom/{item_id}', 'TweetController@getItem');

Route::get('tweets/image/{item_id}', 'TweetController@getImage');

Route::get('tweets/{tweet_id}', 'TweetController@getTweets');

Route::get('tweets/tweetimage/{tweet_id}', 'TweetController@getTweetImage');

Route::get('/getprofile/{user_id}', 'TweetController@getProfile');

Route::post('/register', 'AuthController@register');
Route::post('/register/profile', 'AuthController@registerImage');
Route::post('/login', 'AuthController@login');
Route::get('getuser', 'TweetController@getUser');
    Route::get('tweet/drink/{tweetId}', 'DrinkController@store');
    Route::delete('tweet/drink/{tweetId}', 'DrinkController@destroy');

Route::group(['middleware' => 'auth:api'], function () {
    // 認証が必要なAPIはこの中に記載
    Route::get('/me', 'AuthController@me');
    Route::post('/logout', 'AuthController@logout');


    Route::post('tweet', 'TweetController@tweet');
    Route::post('image', 'TweetController@postFiles');
    //イイネ機能
    Route::get('menulist/favorite/{itemId}', 'FavoriteController@store');
   
    Route::delete('menulist/favorite/{itemId}', 'FavoriteController@destroy');

    
    Route::get('mypage/{userId}', 'DrinkController@myPage');
    
    
    Route::get('getmytweet', 'TweetController@getMyTweet');
    
    Route::get('getallusers', 'AdminController@getUsers');
    
    Route::get('userdel/{user_id}', 'AdminController@userDel');
    
    Route::get('tweetdel/{tweet_id}', 'AdminController@tweetDel');
    
    Route::get('getusertweet/{user_id}', 'TweetController@getAdminMyTweet');


 });
