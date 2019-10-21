import Vue from "vue";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import vuetify from "./plugins/vuetify";
import axios from "axios";
import VueI18n from "vue-i18n";

Vue.use(VueI18n);
Vue.prototype.$http = axios;

const protocol = "http";
const serverName = "192.168.178.69";
const apiPort = "8000";
Vue.prototype.$baseURI =
  protocol + "://" + serverName + ":" + apiPort + "/api/v1/";

Vue.config.productionTip = false;

const messages = {
  en: require("./translations/en.json"),
  nl: require("./translations/nl.json")
};

const i18n = new VueI18n({
  locale: "en",
  messages
});

new Vue({
  router,
  vuetify,
  i18n,
  render: h => h(App)
}).$mount("#app");
