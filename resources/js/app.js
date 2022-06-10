require('./bootstrap');

import Vue from "vue";
import router from "./router";
import VueRouter from "vue-router";
import Toasted from 'vue-toasted';

Vue.use(Toasted);

import Paginate from 'vuejs-paginate';
Vue.component('paginate', Paginate);



axios.interceptors.request.use((request) => {
    // ローカルストレージからauthorization_tokenを取得
    const token = localStorage.getItem('authorization_token');
    // ヘッダーに必要な値を追加
    request.headers = {
        'Authorization': token ? `Bearer ${token}` : '',
        'Accept': "application/json",
        'Content-Type': 'application/json'
    };
    return request
});

// axiosレスポンスインターセプター
axios.interceptors.response.use((response) => {
    // 成功時の処理
    return response;
}, (error) => {
    // エラー発生時の処理
    // 401の場合キャッシュを削除してloginページに飛ばす
    if (error.response.status === 401) {//HTTP レスポンスステータスコード
        app.$toasted.show(`<br>認証エラーが発生しました。<br>&nbsp;`,{duration:4000,theme:"toasted-primary",fullWidth:true,});
        console.error('認証エラー!!');
        localStorage.removeItem('authorization_token');
        router.push({
            name: 'login'
        });
    }
    return error;
});

import Vuetify from 'vuetify';
Vue.use(Vuetify);


import Header from "./components/Header";
import Footer from "./components/Footer";


import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faUserSecret, faMugSaucer, faHeart, faBookmark,faHandPointRight,faHashtag } from '@fortawesome/free-solid-svg-icons';
import { faHeart as regularFaHeart, faBookmark as regularfaBookmark} from '@fortawesome/free-regular-svg-icons';

import VeeValidate, { Validator } from "vee-validate";
import ja from 'vee-validate/dist/locale/ja';
Validator.localize('ja', ja);

Vue.use(VeeValidate, {
  locale: 'ja'
});




const fonts = [
    faUserSecret,
    faMugSaucer,
	  faHeart,
	  regularFaHeart,
	  faBookmark,
	  regularfaBookmark,
	  faHandPointRight,
	  faHashtag
	];

library.add(fonts);
Vue.component('font-awesome-icon', FontAwesomeIcon);

let GlobalData = new Vue({
    data: {
        $isAuthorized: false,
    },
});

Vue.mixin({
    computed: {
        $isAuthorized: {
            get: function () {
                return GlobalData.$data.$isAuthorized;
            },
            set: function (newMsg) {
                GlobalData.$data.$isAuthorized = newMsg;
            },
        },
    },
    watch: {
        // ルート変更時に$isAuthorizedを更新
        $route () {
            this.$isAuthorized = this.authCheck();
        }
    },
    // リロード時も$isAuthorizedを更新
    created () {
        this.$isAuthorized = this.authCheck();
    },
    methods: {
        async validationCheck() {
            await this.$validator.validateAll();
	          console.log("validatorの中身",this.$validator)
            return !this.errors.any();
        },
        authCheck () {
            // ルートのmeta.requireAuthがtrueの場合、認証情報チェック
            const token = localStorage.getItem("authorization_token");
            if (!token) {
                // jwtトークンがない場合はキャッシュを破棄してログインページに遷移
                localStorage.removeItem("authorization_token");
                return false;
            }
            const base64Url = token.split(".")[1];
            const base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
            const decodedToken = JSON.parse(
                decodeURIComponent(escape(window.atob(base64)))
            );
            const expireDate = new Date(decodedToken.exp * 1000);
            const now = new Date();
            const isValidToken = now < expireDate;
            if (isValidToken) {
                // jwtが有効期限内の場合は通常の遷移
                return true;
            } else {
                // jwtの有効期限が切れている場合はキャッシュを破棄してログインページに遷移
                localStorage.removeItem("authorization_token");
                return false;
            }
        }
    },
});


//cors回避
//import cors from "./setting";

window.Vue = require('vue');

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.use(VueRouter);
Vue.component('header-component',Header);

Vue.component('footer-component',Footer);
const app = new Vue({
    el: "#app",
    vuetify: new Vuetify(),
    router
});

