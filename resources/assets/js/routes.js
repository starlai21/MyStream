import VueRouter from 'vue-router';
import store from './store/store';
import * as types from './store/types';


let routes = [
	{
		path:'/',
        name:'home',
		component: require('./views/Home.vue'),
		meta: {
      		keepAlive: true 
    	}
	},

	{
		path:'/archives',
		component: require('./views/Archive.vue'),
		meta: {
      		keepAlive: true 
    	}
	},

	{
		path:'/post/:postId',
		name:'post',
		component: require('./views/Post.vue'),
		meta: {
      		keepAlive: false
    	}
	},

	{
		path:'/admin',
		name:'admin',
		component: require('./views/admin/AdminHome.vue'),
		meta: {
      		requireAuth: true
    	},
    	children:[

    		{
    			path:'posts/manage',
    			name:'posts_manage',
    			component:require('./views/admin/PostsManage.vue') 
    		}

    	]

	},

	{
		path:'/login',
		name:'login',
		component: require('./views/Login.vue'),
	}
];

if (window.localStorage.getItem('token')) {
    store.commit(types.LOGIN, window.localStorage.getItem('token'))
}

const router = new VueRouter({
	routes,
	linkActiveClass: 'is-active'
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(r => r.meta.requireAuth)) {
        if (store.state.token) {
            next();
        }
        else {
            next({
                path: '/login',
                query: {redirect: to.fullPath}
            })
        }
    }
    else {
        next();
    }
})

export default router;