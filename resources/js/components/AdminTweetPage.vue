<template>
<div>
  <template v-for="(mytweet, index) in mytweets">
   <router-link :to="{name:'showtweet',params:{tweet_id:mytweet.id}}">
     <h2 :key="`mytweet_text_${index}`">{{mytweet.text}}</h2>
   </router-link>
   <span class="btn btn-danger" @click="tweetDelete(mytweet.id)">削除</span>
  </template>
</div>
</template>

<script>
export default{
   data(){
       return {
           myitems:[],
	         userId:"",
		       myname:"",
		       mytweets:[],
		       tweetUser:this.$route.params['user_id']
       }
   },
   created(){
     this.userId = this.$route.params['user_id'];
     this.getFavoriteItems();
     this.getMyacount();
     this.getMyTweetItems();
   },
   methods:{
      getFavoriteItems(){
       axios.get(`/api/mypage/${this.userId}`)
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
           this.myname = res.data.screen_name;
           console.log("getuser",res.data);
       }).catch((err)=>{
           console.log(err);
       })
     },
     getMyTweetItems(){
       axios.get(`/api/getusertweet/${this.tweetUser}`)
       .then((res)=>{
           console.log("tweet", res.data);
	         this.mytweets = res.data;
       }).catch((err)=>{
           console.log(err);
       })
     },
     tweetDelete(tweet_id){
       axios.get(`/api/tweetdel/${tweet_id}`)
       .then((res)=>{
         console.log("tweet削除成功");
	       this.getMyTweetItems()
       }).catch((err)=>{
         console.log(err);
       })
     }
   }
}
</script>
