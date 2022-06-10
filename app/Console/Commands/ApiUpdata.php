<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Item;
use Storage;
class ApiUpdata extends Command
{
    /**
The name and signature of the console command.
     *
@var string
     */
    protected $signature = 'command:apiupdata';
    /**
The console command description.
     *
@var string
     */
    protected $description = 'スタバapiは新商品が発売されると情報が追加される。新商品が追加されているかDBと比較して、差分があればDBに追加する。';
    /**
Create a new command instance.
     *
@return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
Execute the console command.
     *
@return mixed
     */
    public function handle(Item $item)
    {
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
	         
	          /**=========================
            apiの画像パスが結構な頻度で変わってしまうので、
            laravelのストレージに保存したい
			      htmlのimgのsrcはpublic配下からの相対パスで画面に表示させる。
　　　　　　webの絶対パスで(http://~)から描画させている場合も、
            スタバのルートディレクトリ以降からのパスをDBに文字列で保存して、
            そのパスでwebから描画させていた。
　　　　　　以下の処理で、webの相対パスのディレクトリ構成のまま、
            Laravelのストレージに保存したので、
            DBの文字列とは差異はない。ので、
            画面に描画させるためにはDBの文字列を取得するという方法でOK。
            =============================*/

            $starbucksUrl = "https://product.starbucks.co.jp";
			      $starbucksImagePath = $menuitem->image1;
			      $url = "{$starbucksUrl}{$starbucksImagePath}";
                  $contents = file_get_contents($url);
                    $name = $starbucksImagePath;
                    Storage::disk('api_images')->put($name, $contents);
	          //画像を保存
		        //file_put_contents($local_path, $img);
	         
            if(!$unique){//空じゃなければ
              $item->create([
              'product_code' => $menuitem->product_code,
              'product_name' => $menuitem->product_name,
              'category' => $menuitem->category2_list_path,
              'item_img' => $menuitem->image1,
              'product_note' => $menuitem->product_note,
              'price' => $menuitem->price,
	            'catchcopy' => $menuitem->catchcopy,
              'sales_day' => $menuitem->sales_day,
               ]);
             }
        }
       $message = '[' . date('Y-m-d h:i:s') . ']ApiUpdata:' . Item::count();
             //INFOレベルでメッセージを出力
	           $this->info( $message );
		         //ログを書き出す処理はこちら
			       Log::setDefaultDriver('batch');
			       Log::info( $message );
    }
}
