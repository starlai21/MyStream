import Vuex from 'vuex';
import Vue from 'vue';
import * as types from './types';
import router from '../routes';


Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        token: null,
        userName: null,
        tempUserName: null,
        blog: null,
        notify: 0
    },
    mutations: {


        [types.NOTIFY]: (state, notify) => {
            state.notify++;
        },

        [types.NAMECHECKED]: (state, {userName, blog}) => {
            state.tempUserName = userName;
            state.blog = blog;
        },

        [types.LOGINED]: (state,token) => {
            state.token = token;
            state.userName = localStorage.userName;
        },

        [types.LOGIN]: (state, {token, userName}) => {
            localStorage.token = token;
            localStorage.userName = userName;
            state.token = token;
            state.userName = userName;
            let params = {};
            params['userName'] = userName;
            router.push({name:'posts_manage', params: params});
        },
        [types.LOGOUT]: (state) => {
            router.push({name:'login'});
            localStorage.removeItem('token');
            localStorage.removeItem('userName');
            state.token = null;

        },
        [types.TITLE]: (state, data) => {
            state.title = data;
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

        logined({commit},{token,userName}){
            return new Promise((resolve,reject) => {
                commit(types.LOGIN,{token,userName});
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
        nameChecked({commit},{userName,blog}){
            commit(types.NAMECHECKED,{userName,blog});
        }
    }
})