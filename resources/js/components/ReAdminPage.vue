<template>
  <div class="readmin">
<v-app id="inspire">
  <h1 class="readmin_h1">管理画面</h1>
  <div v-for="(user,index) in getItems" :key="user.id">
    <div class="card">
      <div class="card-haeder p-3 w-100 d-flex">
		    <div class="ml-2 d-flex flex-column">
             <p class="mb-0 font-weight-bold">ID:{{user.id}}</p>
			       <p class="mb-0 font-weight-bold">ユーザ名:{{ user.name }}</p>
			       <p class="mb-0 font-weight-bold">アカウント名:{{ user.screen_name }}</p>
			       <p class="mb-0 font-weight-bold">メールアドレス:{{ user.email }}</p>
	      </div>
    <v-row justify="space-around">
      <v-col cols="auto">
        <v-dialog
          transition="dialog-bottom-transition"
          max-width="600"
        >
          <template v-slot:activator="{ on, attrs }">
      <router-link :to="{name:'admintweetpage',params:{user_id:user.id}}">
	      <button class="btn primary" style="color:#fff; padding:6px 20px">
	      投稿確認
	      </button>
	    </router-link>
			  <div class="d-flex justify-content-end flex-grow-1">
            <v-btn
              color="red lighten-2"
              v-bind="attrs"
              v-on="on"
            >ユーザ削除</v-btn>
			  </div>
          </template>
          <template v-slot:default="dialog">
            <v-card>
              <v-toolbar
                color="red lighten-2"
                dark
              >要確認！！</v-toolbar>
              <v-card-text>
                <div class="text-h2 pa-12">本当に削除しますか?</div>
              </v-card-text>
              <v-card-actions class="justify-end">
                <v-btn
                  text
                  @click="dialog.value = false"
                >戻る</v-btn>
                <v-btn
                  text
		              color="red lighten-2"
                  @click="userDelete(user.id);dialog.value = false" 
                >削除する</v-btn>
              </v-card-actions>
            </v-card>
          </template>
        </v-dialog>
      </v-col>
    </v-row>
      </div>
    </div>
  </div>
   <paginate
      :page-count="getPageCount"
      :page-range="3"
      :margin-pages="2"
      :click-handler="clickCallback"
      :prev-text="'<'"
      :next-text="'>'"
      :container-class="'pagination justify-content-center'"
      :page-class="'page-item'"
      :page-link-class="'page-link'"
      :prev-class="'page-item'"
      :prev-link-class="'page-link'"
      :next-class="'page-item'"
      :next-link-class="'page-link'"
      :first-last-button="true"
      :first-button-text="'<<'"
      :last-button-text="'>>'"
     >
       </paginate>
  </v-app>
  </div>
</template>
<script>
export default {
  data(){
    return {
      allusers:[],
      user:"",
      parPage:10,
      currentPage:1
    }
  },
  created(){
    this.getAll();
    this.getUserId();
  },
  computed :{
     getItems(){
       let current = this.currentPage * this.parPage;
       let start = current - this.parPage;
       return this.allusers.slice(start, current);
     },
     getPageCount(){
       return Math.ceil(this.allusers.length / this.parPage);
     },
  },
  methods:{
    getAll(){
    axios.get('/api/getallusers/').then((res)=>{
       this.allusers = res.data.data;
       console.log("allUser", this.allusers);
    }).catch((err)=>{
       console.log(err);
    })
   },
   userDelete(userId){
     axios.get(`/api/userdel/${userId}`)
     .then((res)=>{
       console.log(res.data);
       this.getAll()
     }).catch((err)=>{
       console.log(err);
     })
   },
   getUserId(){
     axios.get('/api/getuser/').then((res)=>{
       this.user = res.data;
       if(this.user.role === 'member' ){
         this.$router.push('/notFound');
       }
       console.log("user",res.data);
     }).catch((err)=>{
       console.log(err)
     })
   },
   clickCallback(pageNum) {
      this.currentPage = Number(pageNum);
	  }
  }
}
</script>
<style>
  .readmin {
    width:80vw;
    margin:auto;
  }
  
  .readmin_h1{
    text-align:center;
    padding:20px 0 10px;
  }
</style>
