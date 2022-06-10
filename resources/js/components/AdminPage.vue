<template>
  <div>
        <h1>権限がありませんm(..)m</h1>
  </div>
</template>
<script>
export default {
  data(){
    return {
      user:"",
    }
  },
  created(){
    this.getUserId();
  },
  methods:{
    getUserId(){
      axios.get('/api/getuser/').then((res)=>{
        console.log("USER",res.data);
	      this.user = res.data;
	      if(this.user.role === 'admin' ){
	        this.$toasted.show(`<br>こんにちは！！管理者さん。<br>&nbsp;`,{duration:2500,fullWidth:true,className:"toasted"});
	        this.$router.push('/admin');
	      }else{
	        this.$toasted.show(`<br>スターバックス評価アプリへようこそ！！<br>&nbsp;`,{duration:2500,fullWidth:true,className:"toasted"});
	        this.$router.push('/');
	      }
      }).catch((err)=>{
        console.log(err);
      })
    }
  }
}
</script>
