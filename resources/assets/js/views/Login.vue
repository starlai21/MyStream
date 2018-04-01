<template>
	<div>
		<div class="columns">
  			<div class="column is-half-desktop is-offset-one-quarter has-text-left" >
  				<h1 class="title has-text-grey-light">Login Page</h1>
				<div class="field">
				  <p class="control has-icons-left has-icons-right">
				    <input class="input" type="email" placeholder="Email" v-model="email" @input="clear">
				    <span class="icon is-small is-left">
				      <i class="fa fa-envelope"></i>
				    </span>
				     <p class="help is-danger" v-if="errors.email">{{errors.email[0]}}</p>
				  </p>
				</div>
				<div class="field">
				  <p class="control has-icons-left">
				    <input class="input" type="password" placeholder="Password" v-model="password" @input = "clear" required>
				    <span class="icon is-small is-left">
				      <i class="fa fa-lock"></i>
				    </span>
				    <p class="help is-danger" v-if="errors.password">{{errors.password[0]}}</p>
				  </p>
				</div>
				<div class="field">
				  <p class="control">
				    <button class="button is-primary" @click="login">
				      Login
				    </button>
				  </p>
				</div>
		  	</div>
		</div>
	</div>	
</template>

<script>
	export default {
		data(){
			return{
				email:'',
				password:'',
				errors:[]
			};
		},
		methods:{
			login(){
				if (this.email && this.password)
					axios.post('/api/auth/login',{email:this.email,password:this.password})
						.then(response => {
							this.$store.dispatch('logined',response.data.token);
						})
						.catch(({response}) => {
								this.errors = response.data.errors;
							});
			},
			clear(){
				this.errors = [];
			}
		}
	}
</script>
