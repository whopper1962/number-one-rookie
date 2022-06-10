<template>
<div class="container">
  <h5 class="nonfavorite">
  {{ nonFavorite }}
  </h5>
  <div class="row">
    <template v-for="(myItem, index) in myFavorites">
      <div class="col-lg-3 col-md-4 col-sm-6" v-if="myItem.favorite.length !==0">
       <router-link :to="{name:'showitem',params:{item_id:myItem.id}}" :key="`myItem_router_${index}`">
       <img class="menulist_container-img" :key="`myItemimg_${index}`" :src="`/apiimages${myItem.item_img}`">
       </router-link>
       <p v-html="myItem.product_name" :key="`myItem_name_${index}`">{{ myItem.product_name }}</p>
      </div>
    </template>
  </div>
</div>
</template>
<script>
export default {
  data(){
    return {
      myFavorites: [],
      nonFavorite: ""
    }
  },
  created(){
    this.favoriteMyItems();
  },
  methods:{
    favoriteMyItems(){//filterでやる
      axios.get('/api/getitems/myfavorite').then((res)=>{
        this.myFavorites = res.data;
	      
	      const favoriteFil = this.myFavorites.filter( function(value){
	        return Object.keys(value.favorite).length > 0;
	      })//<
        //0なら
	      this.nonFavorite = favoriteFil.length  === 0
	      ? "まだイイネした商品がありません。menuページからお気に入りのアイテムを見つけてみませんか？" 
	      : "";
	     


	    //==============★ちょっと冗長============
	    /*こいつをfilterで書き直してみる↑↑↑↑↑
	      let isFavorite = false;
        console.log("お気に入りの商品",this.myFavorites);
          this.myFavorites.forEach((value, index) => {
         if(Object.keys(value.favorite).length !== 0){
		        isfavorite = true;
	        }
       })
       this.nonFavorite = isFavorite 
       ? ""
       : "まだイイネした商品がありません。menuページからお気に入りのアイテムを見つけてみませんか？"
      */
	    //========================================

    }).catch((err)=>{
      console.log(err);
    })
  }
}
}
</script>
<style scoped>
 .nonfavorite{
   padding:60px 0 0 0;
 } 
</style>
