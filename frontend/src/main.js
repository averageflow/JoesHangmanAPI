import Vue from "vue";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import vuetify from "./plugins/vuetify";
import axios from "axios";
import VueI18n from "vue-i18n";


function getKeyByValue(object, value) {
  return Object.keys(object).find(key => object[key] === value);
}
function getArrayOfValues(dictionary) {
  return Object.keys(dictionary).map(function (key) {
    return dictionary[key];
  });
}

Vue.use(VueI18n);
Vue.prototype.$http = axios;
const myLocales = { en: "English", nl: "Nederlands", ca: "Català", pt: "Português" };
Vue.prototype.$myLocales = myLocales;
Vue.prototype.$myLanguages = getArrayOfValues(myLocales);

Vue.prototype.$getKeyByValue = getKeyByValue;
Vue.prototype.$getArrayOfValues = getArrayOfValues;

const protocol = "http";
const serverName = "192.168.178.69";
const apiPort = "8000";

Vue.prototype.$baseURI =
  protocol + "://" + serverName + ":" + apiPort + "/api/v1/";

Vue.config.productionTip = false;

const messages = {
  en: require("./translations/en.json"),
  nl: require("./translations/nl.json"),
  ca: require("./translations/ca.json"),
  pt: require("./translations/pt.json")
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
