<template>
<div>
    <div class="register_box">
        <input class="form-control" v-validate="`required`" type="text" v-model="name" name="name" placeholder="Enter あなたのお名前">
	      <span>{{ errors.first('name') }}</span>
        <input class="form-control" type="text" v-validate="`required`" v-model="screen_name" name="screen_name" placeholder="Enter アカウント名">
	      <span>{{ errors.first('screen_name') }}</span>
        <input class="form-control" v-validate="`required|email`" type="text" v-model="email" name="email" placeholder="Enter email">
	      <span>{{ errors.first('email') }}</span>
        <input class="form-control" v-validate="`required`" type="text" v-model="password" name="password" placeholder="Password">
	      <span>{{ errors.first('password') }}</span>
	      <div>
          <input type="file" name="file" ref="preview" @change="fileSelect">
	        <div v-if="url">
		        <img class="profile_image" :src="url">
		      </div>
	      </div>
        <button class="btn btn-primary register_btn" type="submit" @click="register();profileImage()">登録</button>
    </div>
</div>
</template>
<script>
export default {
    data () {
        return {
            name: '',
            screen_name: '',
            email: '',
            password: '',
	          message:"",
		        url:"",
			      selected_file:null,
			      imgName:""
        };
    },
    methods: {
       async register() {
	        const isValid = await this.validationCheck();
		      // nameが与えられているフォームを全てチェックして、もしルール違反をしているものがあったらfalseが帰ってくる
		       if (!isValid) {
			        console.error(`VALIDATION ERROR!`);
				        return;
				    }

            axios.post('/api/register', {
                name: this.name,
                screen_name: this.screen_name,
                email: this.email,
                password: this.password,
		            profile_image: this.imgName
            }).then((res) => {
                const token = res.data.access_token;
		            console.warn(res.data);
                // JWTトークンをローカルストレージに保存
                localStorage.setItem('authorization_token', token);
                // ログイン画面へ遷移
		            this.$toasted.show('<br>会員登録が完了しました！<br>スタバ評価アプリへようこそ！<br>&nbsp;',{duration:4000,fullWidth:true,className:"toasted"});
                this.$router.push('/');
	              console.log("register成功")
            }).catch((err) => {
                console.error(err);
            });
        },
	  fileSelect(e){
	    this.selected_file = e.target.files[0];
	    this.imgName = e.target.files[0].name;
	    const file = this.$refs.preview.files[0];
	    this.url = URL.createObjectURL(file)
	  },
	  profileImage(){
       let formData = new FormData();
       formData.append('file',this.selected_file);

       let config = {
         headers: {
	         'content-type': 'multipart/form-data'
	       }
       };

	    axios.post('/api/register/profile', formData,config)
	    .then((ress)=>{
	      console.log('成功');
	    }).catch((err)=>{console.log(err)})
	  },
    }
}
</script>
<style>
 .profile_image{
   margin-top:30px;
   border-radius: 50%;
   line-height: 200px;
   width:200px;
   height:200px;
 }

.toasted {
   background: linear-gradient(25deg, #F13F79, #FFC778);
}
.register_box {
   padding:40px 0 0 30px;
   width:300px;
}
.register_btn {
  margin:10px 0 0 0;
}
</style>
