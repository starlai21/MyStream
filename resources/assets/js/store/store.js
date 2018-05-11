import Vuex from 'vuex';
import Vue from 'vue';
import * as types from './types';
import router from '../routes';


Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        token: null,
        user: null,
        tempUser: null,
        blog: null,
        notify: 0,
        isLogined: false
    },
    mutations: {


        [types.NOTIFY]: (state, notify) => {
            state.notify++;
        },

        [types.NAMECHECKED]: (state, {tempUser, blog}) => {
            state.tempUser = tempUser;
            state.blog = blog;
        },

        [types.LOGINED]: (state,token) => {
            state.token = token;
            state.user = JSON.parse(localStorage.user);
            state.isLogined = true;
        },

        [types.LOGIN]: (state, {token, user}) => {
            localStorage.token = token;
            localStorage.user = JSON.stringify(user);
            state.token = token;
            state.user = user;
            state.tempUser = user;
            state.isLogined = true;
            let params = {};
            params['userName'] = user.name;
            router.push({name:'posts_manage', params: params});

        },
        [types.LOGOUT]: (state) => {
            router.push({name:'login'});
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            state.token = null;
            state.isLogined = false;
            state.user = null;

        },
        [types.REFRESH_TOKEN]: (state, data) => {
            localStorage.token = data;
            state.token = data;
        }
    },
    actions: {
        logout({commit}){
            return new Promise((resolve,reject) => {
                axios.post('/api/auth/logout')
                        .then(response => {
                            commit(types.LOGOUT);
                        })
                        .catch(r => {
                            console.log(r);
                            commit(types.LOGOUT);
                        });
                Vue.toasted.show("Bye~", { 
                     theme: "primary", 
                     position: "bottom-center", 
                     duration : 3000
                });
                        
            })
        },

        logined({commit},{token,user}){
            return new Promise((resolve,reject) => {
                commit(types.LOGIN,{token,user});
                Vue.toasted.show("Welcome back~", { 
                     theme: "primary", 
                     position: "bottom-center", 
                     duration : 3000
                });
            })
        },
        refreshToken({commit},token){
            commit(types.REFRESH_TOKEN,token);
        },
        nameChecked({commit},{tempUser,blog}){
            commit(types.NAMECHECKED,{tempUser,blog});
        }
    }
})