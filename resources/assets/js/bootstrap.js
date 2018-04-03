
import Vue from 'vue';
import VueRouter from 'vue-router';
import hljs from 'highlight.js';
import 'highlight.js/styles/solarized-light.css';
import marked from 'marked';

// ES6 Modules or TypeScript
import Swal from 'sweetalert2';

// CommonJS
window.Swal = require('sweetalert2');



window.marked = marked;



window.Vue = Vue;
Vue.use(VueRouter);

Vue.directive('highlight',(el)=>{
	let blocks = el.querySelectorAll('pre code');
    blocks.forEach((block) => {
        hljs.highlightBlock(block);
    });
});






window._ = require('lodash');
window.Popper = require('popper.js').default;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    // require('bootstrap');
} catch (e) {}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';



// window.axios.defaults.timeout = 5000;


// // http request 拦截器
// window.axios.interceptors.request.use(
//     config => {
//         if (store.state.token) {
//             config.headers.Authorization = `token ${store.state.token}`;
//         }
//         return config;
//     },
//     err => {
//         return Promise.reject(err);
//     });


// window.axios.interceptors.response.use(
//     (response) => {
//             // 判断一下响应中是否有 token，如果有就直接使用此 token 替换掉本地的 token。你可以根据你的业务需求自己编写更新 token 的逻辑
//             var token = response.headers.authorization
//             if (token) {
//                 // 如果 header 中存在 token，那么触发 refreshToken 方法，替换本地的 token
//                 //this.$store.dispatch('refreshToken', token)
//                 alert('refresh token.')
//             }
//             return response
//         }, (error) => {
//             switch (error.response.status) {
                
//                 // 如果响应中的 http code 为 401，那么则此用户可能 token 失效了之类的，我会触发 logout 方法，清除本地的数据并将用户重定向至登录页面
//                 case 401:
//                    // return this.$store.dispatch('logout')
//                     break
//                 // 如果响应中的 http code 为 400，那么就弹出一条错误提示给用户
//                 case 400:
//                     //return this.$Message.error(error.response.data.error)
//                     break
//             }
//             return Promise.reject(error)
//         })


// // http response 拦截器
// window.axios.interceptors.response.use(
//     response => {
//         return response;
//     },
//     error => {
//         if (error.response) {
//             switch (error.response.status) {
//                 case 401:
//                     // 401 清除token信息并跳转到登录页面
//                     store.commit(types.LOGOUT);
//                     router.replace({
//                         path: 'login',
//                         query: {redirect: router.currentRoute.fullPath}
//                     })
//             }
//         }
//         // console.log(JSON.stringify(error));//console : Error: Request failed with status code 402
//         return Promise.reject(error.response.data)
//     });






/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     encrypted: true
// });
