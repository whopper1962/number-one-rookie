<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>STARBUCKS 評価アプリ</title>

        
	     <script defer src="{{ mix('js/app.js') }}"></script> 
       
       <link rel="stylesheet" href="{{ mix('css/app.css') }}">


    </head>
    <body style="margin:0;">
    <div id="app" style="display:flex; flex-direction:column; min-height:100vh;">
            <header-component></header-component>
            <router-view></router-view>
				    <footer-component></footer-component>
	  </div>
		</body>
</html>
