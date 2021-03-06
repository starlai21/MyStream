import VueRouter from 'vue-router';
import store from './store/store';



function checkUser(userName,next){

    if (store.state.tempUser && store.state.tempUser.name === userName){
        next();
    }
    else{
        axios.get('/api/blog',{params:{'userName':userName}})
                .then(({data}) => {
                    store.dispatch('nameChecked',{tempUser: data.user,blog: data.user.blog});
                    next();
                })
                .catch(e => {
                    next({name:'error'});
                });
    }
}


let routes = [
    

    {
        path:'/',
        component: require('./views/home/Home.vue'),
        children: [
            {
                path:'login',
                name:'login',
                component: require('./views/home/Login.vue')
            },      

            {
                path:'register',
                name: 'register',
                component: require('./views/home/Register.vue')
            },
            {
                path:'',
                name:'root',
                component: require('./views/home/Root.vue')
            },
            {
                path: 'activation/:activation_code',
                name: 'activation',
                component: require('./views/home/Activation.vue'),
                beforeEnter: (to, from, next) => {
                   axios.post('/api/auth/activateUser',{activation_code: to.params.activation_code})
                            .then(response=> {
                                Swal({
                                    type: response.data.status,
                                    title: response.data.message
                                });
                                if (response.data.status === 'success'){
                                    store.dispatch('logined', {
                                        token: response.data.token,
                                        user: response.data.user
                                    });
                                }
                                else{
                                    next({name:'root'});
                                }
                            })
                            .catch(e => {
                                Swal({
                                    type:'error',
                                    title: 'Whoops! something went wrong.'
                                });
                                next({name:'root'});
                            });
                }
            },
            {
                path: 'forgotPassword',
                name: 'forgotPassword',
                component: require('./views/home/ForgotPassword.vue')
            },
            {
                path: 'resetPassword/:code',
                name: 'resetPassword',
                component: require('./views/home/ResetPassword.vue'),
                props: true
            }
        ]
    },

    {
        path:'*',
        name: 'error',
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
    			path:'postsManagement',
    			name:'posts_manage',
    			component:require('./views/admin/PostsManage.vue')
    		},

            {
                path:'blogSetting',
                name: 'blog_setting',
                component: require('./views/admin/BlogSetting.vue')
            }

    	]

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
                if (to.params.userName !== store.state.user.name)
                    next({name:'error'});
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