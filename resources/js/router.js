import Vue from 'vue'
import VueRouter from 'vue-router'

//ページコンポーネントをインポート
import PhotoList from './pages/PhotoList.vue'
import Login from './pages/Login.vue'

import store from './store'
import SystemError from './pages/errors/System.vue'

import PhotoDetail  from './pages/PhotoDetail.vue'

import NotFound from './pages/errors/NotFound.vue'


//VueRouterプラグインを利用する
//これによって<RouterView />などを利用することができる
Vue.use(VueRouter)

//パスとコンポーネントのマッピング
const routes = [
  {
    path: '/',
    component: PhotoList,
    props: route => {
      const page = route.query.page
      return { page: /^[1-9][0-9]*$/.test(page) ? page * 1 : 1 }
    }
  },
  {
    path: '/photos/:id',
    component: PhotoDetail,
    props: true
  },
  {
    path: '/login',
    component: Login,
     beforeEnter (to,from,next){
      if (store.getters['auth/check']) {
        next('/')
      } else {
        next()
      }
    }
  },
  {
    path: '/500',
    component: SystemError
  },
  {
    path: '*',
    component: NotFound
  }
]

//VueRouterインスタンスを作成する
const router = new VueRouter({
  mode: 'history',
  scrollBehavior () {
    return { x: 0, y: 0 }
  },
  routes
})

//VueRouterインスタンスをエクスポートする
//app.jsでインポートするため
export default router
