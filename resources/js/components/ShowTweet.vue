<template>
<div class="showtweet">
      <template v-for="(tweet,index) in tweets">
        <div v-if="myId.id === tweet.user_id || myId.role === 'admin'">
          <button @click=" tweetDel(tweet.id)" class="btn btn-danger">この投稿を削除する</button>
        </div>
        <h5>投稿タイトル</h5>
        <p class="showtweet_item" :key="`tweet_text_${index}`">{{ tweet.text }}</p>
        <h5>クリームのカスタム(量)</h5>
	      <p class="showtweet_item" v-if="!tweet.cream">変更なし</p>
        <p v-else class="showtweet_item" :key="`tweet_cream_${index}`">{{ tweet.cream }}</p>
        <h5>クリームのカスタム(変更)</h5>
	      <p class="showtweet_item" v-if="!tweet.change_cream">変更なし</p>
        <p v-else class="showtweet_item" :key="`tweet_change_cream_${index}`">{{ tweet.change_cream }}</p>
        <h5>ミルクのカスタム(変更)</h5>
	      <p class="showtweet_item" v-if="!tweet.milk">変更なし</p>
        <p v-else class="showtweet_item" :key="`tweet_milk_${index}`">{{ tweet.milk }}</p>
        <h5>パウダーのカスタム(追加)</h5>
	      <p class="showtweet_item" v-if="!tweet.powder">変更なし</p>
        <p v-else class="showtweet_item" :key="`tweet_powder_${index}`">{{ tweet.powder }}</p>
        <h5>ソースのカスタム(追加)</h5>
	      <p class="showtweet_item" v-if="!tweet.source">変更なし</p>
        <p v-else class="showtweet_item" :key="`tweet_source_${index}`">{{ tweet.source }}</p>
        <h5>氷のカスタム(量)</h5>
	      <p class="showtweet_item" v-if="!tweet.ice">変更なし</p>
        <p v-else class="showtweet_item" :key="`tweet_ice_${index}`">{{ tweet.ice }}</p>
        
	      <div v-if="myId.length === 0">
	      </div>
	      <div v-else>
	      <button v-if="tweet.drink.length === 0" @click="addfavorite(tweetId)"><font-awesome-icon class="fa-3x" icon="fa-regular fa-bookmark" /></button>
        <button v-else @click="delfavorite(tweetId)"><font-awesome-icon class="fa-3x" icon="fa-solid fa-bookmark" /></button>
        </div>
      </template>
      <template v-for="(image,index) in images">
        <img
	          :key="`image_${index}`"
		        :src="`/images/${image.image_path}`"
			      style="width:200px"
			      class="showtweet_item"
	      />
      </template>
</div>
</template>
<script>
export default {
  data () {
    return {
      tweetId: null,
      tweets: [],
      images:[],
      itemId: null,
      myId:"",
    }
  },
  created () {
    this.tweetId = this.$route.params['tweet_id'];
    this.getTweet();
    this.getImages();
    this.getMyId();
  },
  methods: {
    getTweet () {
      axios.get(`/api/tweets/${this.tweetId}`).then((res) => {
        this.tweets = res.data;
	      console.log("tweets",this.tweets);
	      this.itemId = res.data.item_id;
      }).catch((err) => {
        console.error(err);
      });
    },
    getImages(){
      axios.get(`/api/tweets/tweetimage/${this.tweetId}`).then((res)=>{
        this.images = res.data;
	      console.log("イメージの配列",this.images);
	     }).catch((erroro)=>{ console.log(error) })
	  },
	  addfavorite(tweetId){
	   axios.get(`/api/tweet/drink/${tweetId}`)
	   .then((res)=>{
       this.getTweet();
	     console.log('イイネされました');
	    }).catch((err)=>{
	     console.log(err);
	    });
	  },
    delfavorite(tweetId){
      axios.delete(`/api/tweet/drink/${tweetId}`)
      .then((res)=>{
      this.getTweet();
        console.log('イイネがはずされました');
	    }).catch((err)=>{
	     console.log(err);
	    });
    },
   getMyId(){
     axios.get('/api/getuser')
     .then((res)=>{
          console.log('私のID',res.data);
       this.myId = res.data;
         }).catch((err)=>{
	     console.log(err);
       })
     },
  tweetDel(tweetId){
    axios.get(`/api/tweetdel/${tweetId}`).then((res)=>{
      console.log('削除成功');
      this.$toasted.show(`<br>削除されました！<br>&nbsp;`,{duration:2500,fullWidth:true,className:"toasted"});
      this.getImages();
      this.getTweet();
      this.$router.push({name: 'mypage'});
    }).catch((err)=>{
      console.log(err);
    })
  }
  }
}
</script>
