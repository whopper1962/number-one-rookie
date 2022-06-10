<template>
<div>
    <div class="login_box">
        <input v-validate="'required|email'" name="email" class="form-control" type="text" v-model="email" placeholder="Enter email">
	      <span>{{ errors.first('email') }}</span>
        <input v-validate="'required'" name="password" class="form-control" type="text" v-model="password" placeholder="Password">
	      <span>{{ errors.first('password') }}</span>
	      <div><button class="btn btn-primary login_btn" type="button" @click="login()">LOGIN</button></div>
        <router-link :to="{name:'register'}"><span class="login_new">新規登録はこちら</span></router-link>
    </div>
</div>
</template>
<script>
export default {
    data () {
        return {
            email: '',
            password: '',
	          user: '',
        };
    },
    methods: {
       login () {
            axios.post('/api/login', {
                email: this.email,
                password: this.password
            }).then((res) => {
                const token = res.data.access_token;
                // JWTトークンをローカルストレージに保存
                localStorage.setItem('authorization_token', token);
	              this.getMyId();
                // メニュー一覧へ遷移
                this.$router.push('/checkadmin');
            }).catch((err) => {
                console.error(err);
            });
        },
        getMyId(){
          axios.get('/api/getuser').then((res)=>{
          this.user = res.data;
          localStorage.setItem('rogin_user', this.user.role);
          localStorage.setItem('rogin_user_id', this.user.id);
          }).catch((err)=>{
          console.log(err);
        })
      }
    },
}
</script>
<style scoped>
  .toasted {
    background: linear-gradient(25deg, #F13F79, #FFC778); 
  }
  .login_box {
    width:300px;
    padding:60px 0 0 30px ;
  }
  .login_btn {
    margin:20px 0 0 0 ;
  }
  .login_new {
    font-size:20px;
  }

</style>

