<template>
<div class="menulist">
  <div class="dropdown">
    <button class="btn btn-outline-info btn-lg  dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        並べ替え</button>
     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
       <li class="dropdown-item" @click="apiview()">すべて</li>
       <li class="dropdown-item" @click="sortPriceDesc()">値段昇順</li>
       <li class="dropdown-item" @click="sortPriceAsc()">値段降順</li>
       <li class="dropdown-item" @click="sortCategory('frappuccino')">frappuccino</li>
       <li class="dropdown-item" @click="sortCategory('drip')">drip</li>
       <li class="dropdown-item" @click="sortCategory('espresso')">espresso</li>
       <li class="dropdown-item" @click="sortCategory('roastery')">roastery</li>
       <li class="dropdown-item" @click="sortCategory('tea')">tea</li>
    </div>
  </div>
  <div class="row menulist_container">
    <template v-for="(item, index) in items">
      <div class="col-lg-3 col-md-4 col-sm-6">
        <router-link :to="{name:'showitem',params:{item_id:item.id}}" :key="`item_router_${index}`">
          <img class="menulist_container-img" :key="`item_img_${index}`" :src="`/apiimages${item.item_img}`">
        </router-link>
        <p v-html="item.product_name" :key="`item_name_${index}`">{{ item.product_name }}</p>
        <p :key="`item_price_${index}`">{{ item.price }}円</p>
	      <template v-if="item.favorite">
		      <span @click="addfavorite(item.id)" class="heart_color_none" v-if="item.favorite.length === 0" :key="`item_iine_${index}`"><font-awesome-icon icon="fa-regular fa-heart" class="fa-lg" /></span>
	        <span @click="delfavorite(item.id)" v-else :key="`item_noiine_${index}`"> <font-awesome-icon class="heart_color_red fa-lg" icon="fa-solid fa-heart" /></span>
		      <span :key="`item_favorite_count_${index}`">{{ item.favorite_count }}</span>
		    </template>
		    <template v-else>
		      <span class="heart_color_none"><font-awesome-icon icon="fa-regular fa-heart" class="fa-lg" /></span>
		      <span :key="`item_favorite_count_${index}`">{{ item.favorite_count }}</span>
		    </template>
	    </div>
    </template>
  </div>
 </div> 
</template>
<script>
export default {
data () {
    return {
        items:[],
	      itemId:'',
	      sortType:'',
    }
},
created(){
    this.apiview();
},
methods:{
  apiview(){
    this.sortType = 'all';
    axios.get('/api/itemsview')
    .then((res)=>{
        this.items = res.data;
	      console.log("favorite情報も一緒に取得",this.items);
    }).catch((err)=>{console.log(err)})
  },
  addfavorite(itemId){
      axios.get(`/api/menulist/favorite/${itemId}`)
      .then((res)=>
      {
        switch(this.sortType){
	          case 'all':
		          this.apiview();
			        break;
				    case 'priceDesc':
				      this.sortPriceDesc();
				      break;
				    case 'priceAsc':
				      this.sortPriceAsc();
				      break;
				    case 'frappuccino':
				      this.sortCategory('frappuccino');
				      break;
				    case 'drip':
				      this.sortCategory('drip');
				      break;
				    case 'espresso':
				      this.sortCategory('espresso');
				      break;
				    case 'roastery':
				      this.sortCategory('roastery');
				      break;
				    case 'tea':
				      this.sortCategory('tea');
				      break;
				    default:
				      this.apiview();
	      }
        console.log('イイネされました');
      }).catch((err)=>{
        console.log(err);
      });
  },
  delfavorite(itemId){
      axios.delete(`/api/menulist/favorite/${itemId}`)
      .then((res)=>{
        switch(this.sortType){
	          case 'all':
		          this.apiview();
			        break;
				    case 'priceDesc':
				      this.sortPriceDesc();
				      break;
				    case 'priceAsc':
				      this.sortPriceAsc();
				      break;
				    case 'frappuccino':
				      this.sortCategory('frappuccino');
				      break;
				    case 'drip':
				      this.sortCategory('drip');
				      break;
				    case 'espresso':
				      this.sortCategory('espresso');
				      break;
				    case 'roastery':
				      this.sortCategory('roastery');
				      break;
				    case 'tea':
				      this.sortCategory('tea');
				      break;
				    default:
				      this.apiview();
	      }
        console.log('イイネがはずされました');
      }).catch((err)=>{
        console.log(err);
      });
  },
  sortPriceDesc(){
    this.sortType = 'priceDesc';
      axios.get('/api/getitems/price/desc')
      .then((res)=>{
        console.log('priceの降順で並び替え');
	      this.items = res.data;
      }).catch((err)=>{console.log(err)})
  },
  sortPriceAsc(){
    this.sortType = 'priceAsc';
      axios.get('/api/getitems/price/asc')
      .then((res)=>{
        console.log('priceの昇順で並び替え');
	      this.items = res.data;
      }).catch((err)=>{
        console.log(err);
      })
  },
  sortCategory(category){
    this.sortType = category;
      axios.get(`/api/getitems/category/${category}`)
      .then((res)=>{
        this.items = res.data;
      }).catch((err)=>{
        console.log(err);
      })
  },
  }
}
</script>
<style>
 .heart_color_red {
    color:red;
    cursor : pointer;
 }
 .heart_color_none {
    cursor : pointer;
 }
</style>