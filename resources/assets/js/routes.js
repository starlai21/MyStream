import VueRouter from 'vue-router';
import store from './store/store';
import * as types from './store/types';



function checkUser(userName,next){
    axios.get('/api/checkUser',{params:{'userName':userName}})
            .then(r => {
                next();
            })
            .catch(e => {
                next({name:'404'});
            });
}


let routes = [

    {
        path:'*',
        name: '404',
        component: require('./views/404.vue')
    },

	{
		path:'/blog/:userName',
        name:'blog_home',
		component: require('./views/blog/Home.vue'),
		meta: {
      		keepAlive: true,
            requireCheck: true
    	}
	},

    {
        path:'/blog/:userName/archives',
        name:'archives',
        component: require('./views/blog/Archive.vue'),
        meta: {
            requireCheck: true
        }
    },

    {
        path:'post/:postId',
        name:'post',
        component: require('./views/blog/Post.vue')
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
	linkActiveClass: 'is-active',
    mode: 'history'
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
    else if (to.matched.some(r => r.meta.requireCheck)){
        checkUser(to.params.userName,next);
    }
    else{
        next();
    }
})

export default router;