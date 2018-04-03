<template>
	<div>
<!-- 		<div class="notification is-primary" v-if="showMessage">
		  <button class="delete" @click="showMessage = false"></button>
		  	Welcome back, admin!
		</div> -->
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
				  <p class="menu-label">
				    Administration
				  </p>
				  <ul class="menu-list">
				    <li><a>Team Settings</a></li>
				    <li><a>Invitations</a></li>
				    <li><a>Authentication</a></li>
				  </ul>
				  <p class="menu-label">
				    Transactions
				  </p>
				  <ul class="menu-list">
				    <li><a>Payments</a></li>
				    <li><a>Transfers</a></li>
				    <li><a>Balance</a></li>
				  </ul>
				</aside>
			</div>
			<div class="column auto">
				<router-view></router-view>	
			</div>
			
		</div>
	</div>
</template>
<script>
	export default {

		data(){
			return {
				showMessage: true
			};
		},
		methods:{

		},
		mounted(){
			setTimeout(() =>{
				this.showMessage = false;
			},3000);
		}
	}

</script>