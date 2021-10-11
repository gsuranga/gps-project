require("./bootstrap");

window.Vue = require("vue");

Vue.component("app-init", require("./AppInit.vue").default);

import VueRouter from "vue-router";
Vue.use(VueRouter);

import Vuetify from "vuetify";
import "vuetify/dist/vuetify.min.css";
Vue.use(Vuetify, {});

import HOME from "./components/Gmap/view";
import NF from "./components/login/notFound";
import LOGIN from "./components/login/login";




//Cheque payment

const router = new VueRouter({
  mode: "history",
  //base: "/laravel_subdir/",
  routes: [
    {
      path: "/",
      name: "HOME",
      component: HOME
    },
    //logins
    {
      path: "/login",
      name: "LOGIN",
      component: LOGIN
    },
    //404 OERSRA
    {
      path: "/*",
      name: "NF",
      component: NF
    }
  ]
});

// route guards
router.beforeEach((to, from, next) => {
  let token = localStorage.getItem("token");
  if (token == null) {
    if (to.name != "LOGIN") {
      localStorage.clear();
      next({ name: "LOGIN" });
    }
  } else {
    if (to.name == "LOGIN") next({ name: "HOME" });
  }

  next();
});
const app = new Vue({
  el: "#app",
  router,
  vuetify: new Vuetify()
});
