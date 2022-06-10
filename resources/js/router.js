import VueRouter from "vue-router";
import ShowItem from "./components/ShowItem";
import MenuList from "./components/MenuList";
import Tweet from "./components/Tweet";
import ShowTweet from "./components/ShowTweet";
import Login from "./components/Login";
import Register from "./components/Register";
import Mypage from "./components/Mypage";
import NotFoundPage from "./components/NotFoundPage";
import AdminPage from "./components/AdminPage";
import ReAdminPage from "./components/ReAdminPage";
import AdminTweetPage from "./components/AdminTweetPage";
import MyFavorite from "./components/MyFavorite";

const routes = [
    {
        path:"/menulist/:item_id",
        component:ShowItem,
        name:"showitem"
    },
    {
        path:"/",
      component:MenuList,
      name:"menulist"
    },
    {
        path:"/tweet",
      component:Tweet,
      name:"tweet",
      meta: {
                 requireAuth: true
        }
  },
  {
      path:"/tweets/:tweet_id",
      component:ShowTweet,
      name:"showtweet"
  },
  {
      path:"/login",
      component:Login,
      name:"login"
  },
  {
      path:"/register",
      component:Register,
      name:"register"
    },
    {
        path:"/mypage/:user_id",
      component:Mypage,
      name:"mypage",
      meta: {
                 requireAuth: true
        }
    },
    {
        path:"/checkadmin",
      component:AdminPage,
      name:"checkadminpage",
      meta: {
                 requireAuth: true
        }
    },
    {
        path:"/admin",
      component:ReAdminPage,
      name:"adminpage",
      meta: {
                 requireAuth: true
        }
    },
    {
        path:"/admin/user/:user_id",
      component:AdminTweetPage,
      name:"admintweetpage",
      meta: {
                 requireAuth: true
        }
    },
    {
        path:"/mypage/user/favorite/:user_id",
      component:MyFavorite,
      name:"myfavorite",
      meta: {
                 requireAuth: true
        }
    },
    {
      //ここで上のルーティングに該当しなかったものをすべてNotFoundPage送りに
      path:'*',
      name:'notFound',
      component:NotFoundPage,
    }

];


const router = new VueRouter({
    mode: 'history',
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.meta.requireAuth) {
        // ルートのmeta.requireAuthがtrueの場合、認証情報チェック
        const token = localStorage.getItem("authorization_token");
        if (!token) {
            // jwtトークンがない場合はキャッシュを破棄してログインページに遷移
            localStorage.removeItem("authorization_token");
            next({ name: "login" });
            return;
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
            next();
        } else {
            // jwtの有効期限が切れている場合はキャッシュを破棄してログインページに遷移
            localStorage.removeItem("authorization_token");
            next({ name: "login" });
        }
    } else {
        next();
    }
});

export default router;
