<template>
    <div class="showitems">
    <div>
        <template v-for="(item,index) in items">
          <h1 class="showitems_ttl" v-html="item.product_name" v-if="item.id == itemId" :key="`item_name_${index}`" />
	        <div class="showitems_flexbox">
		        <div class="showitems_flexbox_imgbox">
		        <img class="showitems_flexbox_imgbox--img" v-if='item.id == itemId' :key="`item_img_${index}`" :src="`/apiimages${item.item_img}`">
	          </div>
		        <div class="showitems_flexbox-item">
		        <h4 class="showitems_flexbox-item--catch" v-html="item.catchcopy" v-if="item.id == itemId" :key="`item_catch_${index}`"/>
		        <p v-html="item.product_note" v-if="item.id == itemId" :key="`item_product_${index}`"/>
		        <p v-if="item.id == itemId" :key="`item_price_${index}`"><span class="fontgray">Tall</span>&nbsp &nbsp &yen;{{ item.price }}</p>
			      <p class="fontgray">価格は税込み価格です</p>
          </div>
	      </div>
	      </template>
        
        <h2 v-if="tweets.length !== 0" class="showitems_custom">みんなのカスタマイズ</h2>
      <div class="showitems_tweet">
        <template v-for="(tweet, index) in tweets">
	<!--tweet.user_idと一致しているユーザのprofile情報を表示させる。-->
	        <div class="showitems_tweet-item">
		       
	        <div class="showitems_tweet-item--avatar">
	          <img :key="`tweet_profile_${index}`" class="showitems_tweet-image" :src="`/profile/${tweet.user.profile_image}`" >
	          <p class="showitems_tweet-item--avatar-user" :key="`tweet_user_${index}`">{{ tweet.user.screen_name }}</p>
	        </div>
		      <h5 class="showitems_tweet-text" :key="`tweet_text_${index}`"><font-awesome-icon icon="fa-solid fa-mug-saucer" />&nbsp;{{ tweet.text }}</h5>
	        <p class="showitems_tweet-cream" :key="`tweet_cream_${index}`">{{ tweet.cream }}</p>
	        <p class="showitems_tweet-change_cream" :key="`tweet_change_cream_${index}`">{{ tweet.change_cream }}</p>
	        <p class="showitems_tweet-milk" :key="`tweet_milk_${index}`">{{ tweet.milk }}</p>
	        <p class="showitems_tweet-powder" :key="`tweet_powder_${index}`">{{ tweet.powder }}</p>
	        <p class="showitems_tweet-source" :key="`tweet_source_${index}`">{{ tweet.source }}</p>
	        <p class="showitems_tweet-ice" :key="`tweet_ice_${index}`">{{ tweet.ice }}</p>
		      <template v-for="(image, i) in imageFilter(tweet.id)">
	          <router-link :to="{name:'showtweet',params:{tweet_id:tweet.id}}">
		        <img
		            :key="`image_${image.id}_${i}`"
			          :src="`/images/${image.image_path}`"
				        style="width: 200px"
			      />
		        </router-link>
		      </template>
	        <router-link :to="{name:'showtweet',params:{tweet_id:tweet.id}}"><div class="showitems_tweet-btn"><button class="btn btn-outline-info" type="button">詳細へ</button></div></router-link>
		      </div>
	        </template>
		  </div>
      </div>
    </div>
</template>

<script>
export default{
   data(){
       return {
           tweets: [],
	         itemId: null,
		       details:{},
		       items:[],
		       images:[],
       }
   },
   created(){
    this.itemId = this.$route.params['item_id'];
    this.getTweets();
    this.getImages();
    this.getDetail();
    this.getItems();
   },
   methods:{
       getTweets(){
           axios.get(`/api/tweets/tweet/${this.itemId}`).then((response) => {
	           this.tweets = response.data;
	       }).catch((error) => {
	           console.error(error);
	       });
	    },
	    getDetail(){
	        axios.get(`/api/tweets/${this.itemId}`).then((response)=>{
			        this.details = response.data;
			        console.log("tweet",this.details);
		      }).catch((error)=>{
		          console.error(error);
		      });
	    },
	    getItems(){//パスパラを引数にして、コントローラでそのパスパラと一致したレコードだけを取得する。
	        axios.get(`/api/tweets/custom/${this.itemId}`).then((response)=>{
		          this.items = response.data;
		      }).catch((error)=>{ console.error(error) })
	    },
	    getImages(){
	        axios.get(`/api/tweets/image/${this.itemId}`).then((response)=>{
		          this.images = response.data;
			        console.log("111",this.images);
		      }).catch((erroro)=>{ console.log(error) })
	    },
	    imageFilter(tweet_id){
	        return this.images.filter(image => {
	            return image.tweet_id === tweet_id
					})
	    },
   }
}
</script>
