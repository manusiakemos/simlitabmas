// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import 'core-js/es6/promise'
import 'core-js/es6/string'
import 'core-js/es7/array'
// import cssVars from 'css-vars-ponyfill'
import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import App from './App'
import router from './router'
import store from './store'

require('../bootstrap');//axios and lodash, not bootstrap css
Vue.use(BootstrapVue);

// import axios from 'axios'
var axiosInstance = axios.create();

axiosInstance.interceptors.request.use(function (config) {
    // assume your access token is stored in local storage
    // (it should really be somewhere more secure but I digress for simplicity)
    if (store.state.auth.status == true && store.state.auth.data) {
        let token = store.state.auth.data.api_token;
        if (token) {
            config.headers['Authorization'] = `Bearer ${token}`
        }
    }
    return config;
}, function (error) {
    // Do something with request error
    // console.log(error);
    return Promise.reject(error);
});

axiosInstance.interceptors.response.use(function (response) {
    // console.log(response);
    return response;
}, function (error) {
    console.error(error);
    if (error.response.status == 401) {
        store.commit("setAuth", null);
        location.assign("/pages/login");
    } else {
        return Promise.reject(error);
    }
});

import VueAxios from 'vue-axios'

Vue.use(VueAxios, axiosInstance);

import './assets/element-ui/theme/index.css';
// import ElementUI from 'element-ui';
import {
    Button,
    Select,
    Option,
    Message,
    MessageBox,
    Breadcrumb,
    BreadcrumbItem,
    Upload,
    Carousel,
    CarouselItem,
    Image,
    Avatar,
    DatePicker
} from 'element-ui';
import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'


// configure language
locale.use(lang)
Vue.use(Button);
Vue.use(Select);
Vue.use(Option);
Vue.use(Breadcrumb);
Vue.use(BreadcrumbItem);
Vue.use(Upload);
Vue.use(Carousel);
Vue.use(CarouselItem);
Vue.use(Image);
Vue.use(Avatar);
Vue.use(DatePicker);
Vue.prototype.$message = Message;
Vue.prototype.$msgbox = MessageBox;
Vue.prototype.$alert = MessageBox.alert;
Vue.prototype.$confirm = MessageBox.confirm;
Vue.prototype.$prompt = MessageBox.prompt;
// Vue.use(MessageBox);

import ImageUploader from 'vue-image-upload-resize'
Vue.use(ImageUploader)


import VueHtmlToPaper from 'vue-html-to-paper';

const options = {
    styles: [
        'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css',
    ]
}

Vue.use(VueHtmlToPaper, options);

/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    store,
    template: '<App/>',
    components: {
        App
    }
});
