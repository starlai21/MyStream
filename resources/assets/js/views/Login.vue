<template>
<section class="hero is-success is-fullheight">
    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="column is-4 is-offset-4">
          <h3 class="title has-text-grey">My Stream - Login</h3>
          <p class="subtitle has-text-grey">Please login to proceed.</p>
          <div class="box">
              <div class="field">
                <div class="control has-icons-left">
                  	<input class="input is-large" type="email" placeholder="Email" v-model="email" @input="clear">
				    <span class="icon is-small is-left">
				      <i class="fa fa-envelope"></i>
				    </span>
				    <p class="help is-danger" v-if="errors.email">{{errors.email[0]}}</p>
                </div>
              </div>

              <div class="field">
                <div class="control has-icons-left">
                   <input class="input is-large" type="password" placeholder="Password" v-model="password" @input = "clear" required>
				    <span class="icon is-small is-left">
				      <i class="fa fa-lock"></i>
				    </span>
				    <p class="help is-danger" v-if="errors.password">{{errors.password[0]}}</p>
                </div>
              </div>
              <div class="field">
              </div>
              <button class="button is-block is-primary is-large is-fullwidth" @click="login">Login</button>
          </div>
          <p class="has-text-grey">
            <a href="../">Sign Up</a> &nbsp;·&nbsp;
            <a href="../">Forgot Password</a> &nbsp;·&nbsp;
            <a href="../">Need Help?</a>
          </p>
        </div>
      </div>
    </div>
  </section>
</template>
<style type="text/css">
	html,body {
  font-family: 'Open Sans', serif;
  font-size: 16px;
  font-weight: 300;
}
.hero.is-success {
  background: #F2F6FA;
}
.hero .nav, .hero.is-success .nav {
  -webkit-box-shadow: none;
  box-shadow: none;
}
.box {
  margin-top: 5rem;
}
.avatar {
  margin-top: -70px;
  padding-bottom: 20px;
}

input {
  font-weight: 300;
}
p {
  font-weight: 700;
}
p.subtitle {
  padding-top: 1rem;
}
</style>
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
							this.$store.dispatch('logined',{token: response.data.token,userName: response.data.userName});
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
