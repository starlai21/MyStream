
import Vue from 'vue';
import VueRouter from 'vue-router';
import hljs from 'highlight.js';



// ES6 Modules or TypeScript
import Swal from 'sweetalert2';

import Toasted from 'vue-toasted';

import VeeValidate from 'vee-validate';

import mavonEditor from 'mavon-editor';
import 'mavon-editor/dist/css/index.css';
import 'highlight.js/styles/solarized-light.css';
import Affix from 'vue-affix'

import Tooltip from 'vue-directive-tooltip';
import 'vue-directive-tooltip/css/index.css';

import axios from 'axios';
import VueAxios from 'vue-axios';
import VueAuthenticate from 'vue-authenticate';



// CommonJS
window.Swal = require('sweetalert2');



Vue.use(Tooltip,{
	delay: 0
});
Vue.use(Toasted);
Vue.use(VueRouter);
Vue.use(VeeValidate);

Vue.use(mavonEditor);

// var VueAffix = require('vue-affix').default;
Vue.use(Affix);
var host = window.location.hostname;

var baseUrl = "http://stream.test/";
var redirectUri = "http://stream.test/";
var clientId = '47cbca2404878e6637ab';
var clientSecret = '90ad2bdb2db2b14ceeac4be7035e2cbd8f949b1a';
if (host === 'laohubushimao.com' || host === 'www.laohubushimao.com'){
  clientId = '7ef439909543190bfe1b';
  clientSecret = 'f6c41fd5b708ae05bd6ea9e0e4f20a40c6a31464';
  baseUrl = "https://"+host;
  redirectUri = "https://"+host;
}


Vue.use(VueAxios, axios);
Vue.use(VueAuthenticate, {
  	baseUrl: baseUrl, // Your API domain
  	providers: {
    github: {
    	url: 'api/auth/github',
      	clientId: clientId,
      	clientSecret: clientSecret,
      	redirectUri: redirectUri // Your client app URL
    }
  }
})



Vue.directive('highlight',(el)=> {
	Vue.nextTick(()=>{
		let blocks = el.querySelectorAll('pre code');
	    blocks.forEach((block) => {
	        hljs.highlightBlock(block);
	    });
	});
});
window.Vue = Vue;





window._ = require('lodash');
// window.Popper = require('popper.js').default;

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

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';





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
