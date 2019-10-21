import Vue from 'vue'
import VueRouter from 'vue-router'
import LoginForm from '../views/LoginForm'
import RegisterForm from '../views/RegisterForm'
import Game from '../views/Game'
import InsertWord from '../views/InsertWord'
import VueCookies from 'vue-cookies'

Vue.use(VueRouter)
Vue.use(VueCookies)

const routes = [
  { path: '/register', name: 'register', component: RegisterForm },
  { path: '/login', name: 'login', component: LoginForm },
  {
    path: '/',
    name: 'game',
    component: Game,
    props: { authenticated: new Boolean(VueCookies.get('authenticated')) }
  },
  {
    path: '/insert_word',
    name: 'insert_word',
    component: InsertWord,
    props: { authenticated: new Boolean(VueCookies.get('authenticated')) }
  }
]

const router = new VueRouter({
  routes
})

export default router
