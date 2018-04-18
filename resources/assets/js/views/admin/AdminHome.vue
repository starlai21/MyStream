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
						    <li><a>Dashboard</a></li>
						    <router-link :to="{name:'posts_manage'}">Posts Management</router-link>
						  </ul>
						</aside>
					</div>
					<div class="column auto">
						<router-view></router-view>	
					</div>
					
				</div>
			</div>
		</section>
	</div>
</template>
<script>
import Header from '../blog/Header';
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
		}
	}

</script>