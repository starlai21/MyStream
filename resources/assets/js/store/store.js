import Vuex from 'vuex';
import Vue from 'vue';
import * as types from './types';
import router from '../routes';


Vue.use(Vuex);
export default new Vuex.Store({
    state: {
        user: {},
        token: null,
        title: ''
    },
    mutations: {
        [types.LOGIN]: (state, data) => {
            localStorage.token = data;
            state.token = data;
        },
        [types.LOGOUT]: (state) => {
            localStorage.removeItem('token');
            state.token = null
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
                Swal({
                      title: 'Bye~'
                    });
                        router.push({name:'login'});
                        
            })
        },

        logined({commit},token){
            return new Promise((resolve,reject) => {
                commit(types.LOGIN,token);
                Swal({
                      title: 'Welcome back!'
                    });
                router.push({name:'admin'});
            })
        },
        refreshToken({commit},token){
            commit(types.REFRESH_TOKEN,token);
        }
    }
})