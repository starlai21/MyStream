<template>
	<div>
		<blog-header></blog-header>
		<section class="section">
    		<div class="container body">
				<div class="columns">
					<div class="column is-one-fifth">
						<aside class="menu">
						  <p class="menu-label">
						    General
						  </p>
						  <ul class="menu-list">
						    <router-link :to="{name:'blog_setting'}">Blog Setting</router-link>
						    <router-link :to="{name:'posts_manage'}">Posts Management</router-link>
						  </ul>
						</aside>
					</div>
					<div class="column auto">
						<keep-alive>
							<router-view></router-view>	
						</keep-alive>
					</div>
					
				</div>
			</div>
		</section>
		<blog-footer></blog-footer>
	</div>
</template>
<script>
import Header from '../blog/Header';
import {mapState} from 'vuex';

	export default {

		data(){
			return {
				
			};
		},
		methods:{

		},
		mounted(){

		},
		components:{
			'blog-header': Header
		},
    	computed: mapState({
    		userName: state => state.tempUserName,
    		loginedUserName: state => state.userName,
    		blog: state => state.blog
    	}),
	}

</script>