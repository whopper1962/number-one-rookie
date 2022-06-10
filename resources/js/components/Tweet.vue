<template>
  <div class="tweet">
    <v-app>
        <div class="tweet_container">
        <h1 class="tweet_container-h1">カスタム投稿</h1>
	      <form>
	      <div class="tweet_container-box">
	      <p class="ttl">商品名</p>
        <v-autocomplete
            v-model="newtweet.itemNameId"
            :items="allItemsName"
            item-value="id"
            item-text="name"
            dense
            filled
            label="item_name"
            return-object
        >
        </v-autocomplete>
	      <div class="tweet_container-box--text">
          <p class="ttl">投稿タイトル:</p>
          <textarea class="form-control" v-validate="`required`" type="text" v-model="newtweet.text" name="title" />
	        <span>{{ errors.first('title') }}</span>
	      </div>
	      <div class="tweet_container-box--cream tweet_container-box--text">
          <p class="ttl">ホイップクリーム量:</p>
          <select class="form-select" v-model="newtweet.cream">
              <option value="">-</option>
              <option>なし(ノンホイップ)</option>
              <option>少なめ(ライトホイップ)</option>
              <option>増量(エクストラホイップ)</option>
          </select>
	      </div>
	      <div class="tweet_container-box--changecream tweet_container-box--text">
          <p class="ttl">ホイップクリーム変更:</p>
          <select class="form-select" v-model="newtweet.change_cream">
              <option value="">-</option>
              <option>生チョコレートホイップに変更</option>
              <option>コーヒークリームに変更</option>
          </select>
        </div>
	      <div class="tweet_container-box--milk tweet_container-box--text">
	        <p class="ttl">ミルク変更:</p>
          <select class="form-select" v-model="newtweet.milk">
              <option value="">-</option>
              <option>低脂肪乳に変更(ツーパーセント)</option>
              <option>無脂肪乳に変更(ノンファット)</option>
              <option>ソイミルクに変更</option>
              <option>オーツミルクに変更</option>
              <option>ブレドミルクに変更</option>
              <option>アーモンドミルクに変更</option>
          </select>
        </div>
	      <div class="tweet_container-box--powder tweet_container-box--text">
	        <p class="ttl">パウダー追加:</p>
          <select class="form-select" v-model="newtweet.powder">
              <option value="">-</option>
              <option>ココアパウダー追加</option>
              <option>シナモンパウダー</option>
          </select>
        </div>
	      <div class="tweet_container-box--source tweet_container-box--text">
	        <p class="ttl">ソース追加:</p>
          <select class="form-select" v-model="newtweet.source">
              <option value="">-</option>
              <option>チョコソース追加</option>
              <option>キャラメルソース追加</option>
              <option>はちみつ追加</option>
          </select>
        </div>
	      <div class="tweet_container-box--ice tweet_container-box--text">
	        <p class="ttl">氷の量:</p>
          <select class="form-select" v-model="newtweet.ice">
              <option value="">-</option>
              <option>多め(エクストラアイス)</option>
              <option>少なめ(ライトアイス)</option>
          </select>
        </div>
	      <div class="tweet_container-box--file">
	        <label>画像をアップロードする<span style="font-size:0.8rem; color:#333;">&nbsp;&nbsp; ※最大4枚まで</span></label>
          <input type="file" @change="fileChanged" accept=".jpg, .jpeg, .png" :disabled="isImgCount"/>
          <div v-for="(thumbnail, index) in thumbnails" :key="`file_${index}`">
              <img :src="thumbnail" style="width: 200px" />
              <!-- 選択した画像を消す -->
              <button @click.prevent="deleteImage(index)">Cancel</button>
          </div>
	      </div>
        <button type="button" class="btn btn-info tweet_container-box--btn" @click.prevent="tweetCreate">送信</button>
	      </div>
        </form>
	      </div>
    </v-app>
  </div>
</template>
<script>
const MAX_THUMBNAIL_NUM = 4;
export default {
    data() {
        return {
            newtweet: {
                text: "",
                cream: "",
                change_cream: "",
		            milk:"",
			          powder:"",
			          source:"",
				        hot:null,
			          ice:"",
                itemNameId: "",
            },
            items: "",
            //itemid: [],
            allItemsName: [],
            thumbnails: [],
            filesData: [],
	          menuitems:[],
		        user:"",
			      isImage: false
        };
    },
    created() {
        this.getProductName();
	      this.getUserId();
    },
    computed:{
        isImgCount(){
	       if(this.thumbnails.length < MAX_THUMBNAIL_NUM){
	          this.isImage = false;
		        console.log(this.isImage);
	       }else{
	          this.isImage = true;
			      return this.isImage;
	       }
	      //this.thumbnailsが4つ選択されているか
	    }
    },
    methods: {
         async tweetCreate() {
	          //バリデーション
		        const isValid = await this.validationCheck();
			      //nameが与えられているフォームをすべてチェックして、もしルール違反をしているものがいたらfalseが返る。
			      if(!isValid){
			          console.error('VALIDATION ERROR!');
				        return;
			      }
            //上記のバリデーションチェックに引っかかったら以下の登録処理はしない。
            let formData = new FormData();
            // 配列fileに選択された画像ファイルを追加する
            this.filesData.forEach((file, index) => {
                formData.append("file[]", file);
            });
            // this.newtweetのまま送ると、"Object[object]"という文字列が送られる
            // JSON.stringfyをしてあげて、オブジェクトを文字列として送る
            formData.append("kinds[]", JSON.stringify(this.newtweet));
            axios.post(`/api/tweet`, formData).then(() => {
                console.log("登録処理が成功しました!");
                // 商品詳細へ飛ばしてもいいかも
		            this.$router.push(`/mypage/${this.user}`);
            }).catch((error) => {
                console.error(error);
            });
        },
        // 対象の画像を削除
        deleteImage(index) {
            this.filesData.splice(index, 1);
            this.thumbnails.splice(index, 1);
        },
        // サムネイル表示、画像データの保存
        fileChanged(event) {
            this.fileInfo = event.target.files[0];
            let files = event.target.files || event.dataTransfer.files;
	    　　　//画像が4枚選択されている場合は、push出来なくする。
	          //filesDataは送信apiに送るデータを格納して、thumbnailsには画面に表示するデータを格納
            this.filesData.push(files[0]);
            let reader = new FileReader();
            reader.onload = (e) => {
                this.thumbnails.push(e.target.result);
            };
            reader.readAsDataURL(files[0]);
        },
        // フォームを空にする
        resetForms() {
            this.newtweet = {
                item: "",
                text: "",
                topping: "",
                tea: "",
            };
            this.thumbnails = [];
            this.filesData = [];
        },
        getProductName() {
            axios.get("/api/itemsview")
              .then((res) => {//ここはちゃんと理解した。
               const allItems = res.data;
               //this.itemid = allItems.map(item => item.id);//idのみが入った配列がライブラリで必要なため。..と思ってたけど不要だった。。
               this.allItemsName = allItems.map(item => {
                 return {
                   name:item.product_name.replace(/&reg;|&sup3;|&trade;/g,''),
                   id: item.id,
                 }
               })

             /*====★上記のように修正した。不要なこといっぱい========
                this.items = res.data;
                let itemids = [];
                let obj = [];
                for (let i in this.items) {
                    let item = this.items[i].product_name;
                    let itemid = this.items[i].id;
				            let replaceName = this.items[i].product_name.replace(/&reg;|&sup3;|&trade;/g,'' );
                    itemids.push(itemid);
                    obj.push({
                        name: replaceName,
                        id: this.items[i].id,
                    });
                }
                this.itemid = itemids;
                this.itemNameId_obj = obj;
             ======================================================*/
            }).catch((err) => {
                console.error(err);
            });
        },
	      getUserId(){
	         axios.get('/api/getuser/').then((res)=>{
	         console.log("USER",res.data);
		       this.user = res.data;
		       }).catch((err)=>{
		         console.log(err);
			     })
			  }
    }
};
</script>
