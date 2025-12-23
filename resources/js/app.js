import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import Vue3ApexCharts from "vue3-apexcharts";

const app = createApp(App);
app.use(router);
app.use(Vue3ApexCharts);
app.mount('#app');
