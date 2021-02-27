/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");
window.Form = Form;
window.Swal = Swal;

import { Form, HasError, AlertError } from "vform";

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

import Swal from "sweetalert2";
import VueRouter from "vue-router";
import moment from "moment";
import Datatable from "vue2-datatable-component";
import Vuetify from "vuetify";
import App from "./views/App";
import Gate from "./gate";

import ApexCharts from "apexcharts";
import VueApexCharts from "vue-apexcharts";

Vue.component("apexchart", VueApexCharts);
Vue.prototype.$gate = new Gate(window.user);

//VCalendare Error message for native Html
const ignoreWarnMessage =
  "The .native modifier for v-on is only valid on components but it was used on <div>.";
Vue.config.warnHandler = function(msg, vm, trace) {
  // `trace` is the component hierarchy trace
  if (msg === ignoreWarnMessage) {
    msg = null;
    vm = null;
    trace = null;
  }
};



// VUe Print
import Print from 'vue-print-nb'
Vue.use(Print);

import VueQrPrint from 'vue-qr-print'
Vue.use(VueQrPrint);

// Vuetify
Vue.use(Vuetify);

//QR-CODE
import VueQrcode from '@chenfengyuan/vue-qrcode';
Vue.component(VueQrcode.name, VueQrcode);

import VueQrcodeReader from "vue-qrcode-reader";
Vue.use(VueQrcodeReader);


// Datatable Vue2
Vue.use(Datatable);

import VueProgressBar from "vue-progressbar";
Vue.use(VueProgressBar, {
  color: "rgb(143, 255, 199)",
  failedColor: "red",
  height: "3px"
});

Vue.use(VueRouter);

let routes = [
  {
    path: "/dashboard",
    component: require("./components/Dashboard.vue").default
  },
  { path: "/home", component: require("./components/Home.vue").default },
  { path: "/profile", component: require("./components/Profile.vue").default },
  { path: "/faculty", component: require("./components/Faculty.vue").default },
  { path: "/faculty", component: require("./components/Student.vue").default },
  { path: "/faculty", component: require("./components/Schedule.vue").default },
  { path: "/rooms", component: require("./components/Rooms.vue").default },
  {
    path: "/schedule",
    props: { default: true },
    component: require("./components/Schedule.vue").default
  }
];

const router = new VueRouter({
  mode: "history",
  routes // short for `routes: routes`
});
window.moment = moment;

Vue.filter("upText", function(text) {
  return text.charAt(0).toUpperCase() + text.slice(1);
});

Vue.filter("myDate", function(created) {
  return moment().format("MMMM Do , YYYY");
});

Vue.filter("shortDate", function(created) {
  return moment(created).format("ll");
});

Vue.filter("numDate", function(created) {
  return moment(created).format("l");
});

Vue.filter("logTime", function(created) {
  return moment(created).format("LT");
});

window.Fire = new Vue();

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component(
  "example-component",
  require("./components/ExampleComponent.vue").default
);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
  el: "#app",

  router,
  render: h => h(App),
  vuetify: new Vuetify()
});

export default app;
