<template>
   <div class="header">
   <div class="container">
    <div class="header_box row">
        <template v-if="user === 'admin'">
          <div class="header_box-logo col">
	          <img class="header_img" src="/images/logo.jpg" style="width:150px">
          </div>
	      </template>
        <template v-else>
          <div class="header_box-logo col">
            <router-link :to="{name:'menulist'}">
	            <img class="header_img" src="/images/logo.jpg" style="width:150px">
	          </router-link>
		      </div>
		      <div class="header_box-nav col">
	          <router-link :to="{name:'menulist'}"><p class="header_menu">menu</p></router-link>
            <router-link :to="{name:'mypage' ,params:{user_id:myId}}"><p class="header_menu">mypage</p></router-link>
	          <router-link :to="{name:'tweet'}"><p class="header_menu">post</p></router-link>
          </div>
	      </template>
	      <div class="header_box-btn col">
	        <!-- ログインしている場合は、ログアウトボタンを表示 -->
	        <button v-if="$isAuthorized" class="btn btn-secondary" @click="logout()">logout</button>
          <!-- ログインしていない、かつログインページにいない場合はログインボタンを表示 -->
          <router-link :to="{name:'login'}" v-if="!$isAuthorized & !isLoginPage" class="btn btn-primary" @click="logout()">
           login
          </router-link>
	      </div>
	    </div>
	    </div>
   </div>
</template>
<script>
export default {
    data(){
        return {
	        title:"Header",
		      myId:localStorage.getItem('rogin_user_id'),
		      user: localStorage.getItem('rogin_user')
	    };
    },
watch: {
    // ルート変更時にuserを更新
        $route () {
	        this.user = localStorage.getItem('rogin_user');
	        this.myId = localStorage.getItem('rogin_user_id');
      }
    },
    computed:{
       isLoginPage () {
            // 今いるページがログインページかどうかを判定
            return this.$route.name === 'login';
       }
    },
    methods:{
        logout () {
            axios.post('/api/logout').then((res) => {
                // ローカルストレージからJWTトークンを破棄
                localStorage.removeItem('authorization_token');
                localStorage.removeItem('rogin_user');
                // ログイン画面へ遷移
                this.$router.push({
                    name: 'login'
                });
            }).catch((err) => {
                console.error(err);
            });
        },
	}
}
</script>