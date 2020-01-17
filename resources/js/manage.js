import Vue from 'vue';
import VueRouter from 'vue-router';
import App from './manage/layouts/App.vue';


import routes from './manage/routes';

const router = new VueRouter({
  routes,
  mode: 'history',
});

Vue.use(VueRouter);

new Vue({
  el: '#app',
  router,
  render: h => h(App),
});
