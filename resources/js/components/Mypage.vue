<template>
<div class="mypage">
<div class="mypage_bk">
<h1 class="mypage_h1">こんにちは！！{{ myname.screen_name }}さん</h1>
<h2 class="mypage_h2"><font-awesome-icon icon="fa-solid fa-bookmark" />&nbsp;保存したカスタマイズ</h2>
   <template v-if="myitems.length === 0">
     <h5 class="mypage_h5">まだカスタムを保存していません。<br>menuページからお気に入りのカスタムを見つけてみませんか？</h5>
   </template>
   <template v-for="(array,index) in myitems">
     <template v-for="(item, i) in array">
       <div class="mypage_flexbox">
         <div class="mypage_flexbox-item">
           <h3 class="mypage_ttl" :key="`text_${index}`"> {{ item.text }} </h3>
	         <div class="mypage_flexbox-item--box">
	           <p class="mypage_menu"><font-awesome-icon icon="fa-solid fa-mug-saucer" />カスタム内容</p>
             <p class="mypage_txt" :key="`cream_${index}`"> {{ item.cream }} </p>
             <p class="mypage_txt" :key="`change_cream_${index}`"> {{ item.change_cream }} </p>
             <p class="mypage_txt" :key="`milk_${index}`"> {{ item.milk }} </p>
             <p class="mypage_txt" :key="`powder_${index}`"> {{ item.powder }} </p>
             <p class="mypage_txt" :key="`source_${index}`"> {{ item.source }} </p>
             <p class="mypage_txt" :key="`ice_${index}`"> {{ item.ice }} </p>
              <router-link :to="{name:'showtweet',params:{tweet_id:item.id}}">
                <button type="button" class="btn btn-outline-info">詳細へ</button> 
	            </router-link>
          </div>
         </div>
         <section class="mypage_section">
           <v-carousel hide-delimiters class="mypage_container">
            <v-carousel-item
              v-for="(img, i) in item.image"
	            :key="`image_${img.id}_${i}`"
	            class="mypage_content"
	          >
	         <div class="mypage_slide">
              <router-link :to="{name:'showtweet',params:{tweet_id:item.id}}">
	            <div class="mypage_box">
                <img class="mypage_box-img"
	               :src="`/images/${img.image_path}`"
                />
	            </div>
              </router-link>
            </div>
         </v-carousel-item>
         </v-carousel>
       </section>
   </div>
  </template>
  </template>
  <div class="mypage_favorite">
  <router-link :to="{name:'myfavorite',params:{user_id:myname.id}}">
  <h2 class="mypage_favorite-h2"><font-awesome-icon class="heart_color_red fa-lg" icon="fa-solid fa-heart" />&nbsp;イイネした商品を見る</h2>
  </router-link>
  </div>
  <h2 class="mypage_mypost"><font-awesome-icon icon="fa-solid fa-hashtag" />&nbsp;自分の投稿を見る</h2>
   <div class="mypage_mytweet">
     <div class="mypage_mytweet-box">
     <template v-if="mytweets.length === 0">
         <h5 class="mypage_mytweet-h5">まだ投稿がありません。postページから投稿してみませんか？</h5>
     </template>
     <template v-else>
     <template v-for="(mytweet, index) in mytweets">
       <router-link :to="{name:'showtweet',params:{tweet_id:mytweet.id}}">
         <h2 class="mypage_mytweet-h2" :key="`mytweet_text_${index}`"><font-awesome-icon icon="fa-solid fa-hand-point-right" />&nbsp;{{mytweet.text}}</h2>
       </router-link>
    </template>
  </template>
     </div>
   </div>
</div>
</div>
</template>

<script>
export default{
   data(){
       return {
           myitems:[],
		       myname:"",
		       mytweets:[]
       }
   },
   created(){
     this.getFavoriteItems();
     this.getMyacount();
     this.getMyTweetItems();
   },
   methods:{
      getFavoriteItems(){
       axios.get(`/api/mypage/${this.myname.id}`)
       .then((res)=>{
           console.log("保存したドリンク取得",res.data);
	         this.myitems = res.data;
       }).catch((err)=>{
          console.log(err);
      })
    },
     getMyacount(){
       axios.get('/api/getuser')
       .then((res)=>{
           this.myname = res.data;
           console.log("getuser",res.data);
       }).catch((err)=>{
           console.log(err);
       })
     },
     getMyTweetItems(){
       axios.get('/api/getmytweet')
       .then((res)=>{
           console.log(res.data);
	         this.mytweets = res.data;
       }).catch((err)=>{
           console.log(err);
       })
     }
   }
}
</script>
