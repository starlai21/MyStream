import VueRouter from 'vue-router';
import store from './store/store';



function checkUser(userName,next){
 
    if (store.state.tempUserName && store.state.tempUserName === userName){
        next();
    }
    else{
        axios.get('/api/checkUser',{params:{'userName':userName}})
                .then(({data}) => {
                    store.dispatch('nameChecked',{userName: userName,blog: data.blog});
                    next();
                })
                .catch(e => {
                    next({name:'404'});
                });
    }

            // axios.get('/api/checkUser',{params:{'userName':userName}})
            //     .then(({data}) => {
            //         store.dispatch('nameChecked',{userName: userName,blog: data.blog});
            //         next();
            //     })
            //     .catch(e => {
            //         next({name:'404'});
            //     });


}


let routes = [
    

    {
        path:'/',
        name: 'home',
        component: require('./views/Home.vue')
    },

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
            keepAlive: true,
            requireCheck: true
        }
    },

    {
        path:'/blog/:userName/post/:postId',
        name:'post',
        component: require('./views/blog/Post.vue'),
        props: true,
        meta: {
            requireCheck: true,
            keepAlive: false
        }
    },    

	{
		path:'/blog/:userName/admin',
		name:'admin',
		component: require('./views/admin/AdminHome.vue'),
		meta: {
      		requireAuth: true,
            requireCheck: true,
            keepAlive: true
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


const router = new VueRouter({
	routes,
	linkActiveClass: 'is-active',
    mode: 'history'
});



router.beforeEach((to, from, next) => {
    if (to.matched.some(r => r.meta.requireAuth)) {
        if (store.state.token) {
            if (to.matched.some(r => r.meta.requireCheck)){
                if (to.params.userName !== store.state.userName)
                    next({name:'404'});
                else
                    checkUser(to.params.userName,next);
            }
            else
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