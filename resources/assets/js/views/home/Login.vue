<template>

    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="column is-4 is-offset-4">
          <h3 class="title">My Stream - Sign In</h3>
          <p class="subtitle">Please sign in to proceed.</p>
          <div class="box">
              <div class="field">
                <div class="control has-icons-left has-icons-right">
                  	<input name="email" type="email" placeholder="Email" v-model="email" @input="clear" v-validate="'required|email'" data-vv-delay="1000" :class="{'input': true, 'is-danger': errors.has('email')}">
				            <span class="icon is-small is-left">
				              <i class="fa fa-envelope"></i>
				            </span>
                    <span class="icon is-small is-right">
                      <i v-show="errors.has('email')" class="fa fa-warning"></i>
                    </span>
                    <span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>
				            <span class="help is-danger" v-if="login_errors.email">{{login_errors.email[0]}}</span>
                </div>
              </div>

              <div class="field">
                <div class="control has-icons-left has-icons-right">
                   <input name="password" type="password" placeholder="Password" v-model="password" @input = "clear" required  v-validate="{min: 6, max:20}" data-vv-delay="1000" :class="{'input': true, 'is-danger': errors.has('password')}" @keyup.enter="login">
				          <span class="icon is-small is-left">
				            <i class="fa fa-lock"></i>
				          </span>
                   <span class="icon is-small is-right">
                      <i v-show="errors.has('password')" class="fa fa-warning"></i>
                    </span>
                    <span v-show="errors.has('password')" class="help is-danger">{{ errors.first('password') }}
                    </span>
				          <span class="help is-danger" v-if="login_errors.password">{{login_errors.password[0]}}</span>
                </div>
              </div>

              <a class="button is-block is-primary is-fullwidth" @click="login">Sign in</a>
              or
              <a class="button is-block is-dark is-fullwidth" @click="loginWith('github')">
                <span class="icon is-left"><i class="fa fa-github"></i></span>&nbsp
                Sign in with github
              </a>

          </div>
          <p>
            <router-link :to="{name:'register'}">Sign up</router-link> &nbsp;·&nbsp;
            <router-link :to="{name:'forgotPassword'}">Forgot password</router-link>          
          </p>
        </div>
      </div>
    </div>

</template>
<style type="text/css">

.hero .nav, .hero.is-success .nav {
  -webkit-box-shadow: none;
  box-shadow: none;
}

input {
  font-weight: 300;
}
p {
  /*font-weight: 700;*/
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
				login_errors:[]
			};
		},
		methods:{
			login(){
				if (this.email && this.password){
          this.$validator.validateAll().then((result) =>{
            if (result){
              axios.post('/api/auth/login',{email:this.email,password:this.password})
                      .then(response => {
                        if (response.data.status === 'success'){
                          var redirectUrl = this.$route.query.redirect;
                          this.$store.dispatch('logined',{token: response.data.token,user: response.data.user});

                          if (redirectUrl){
                            this.$router.push(redirectUrl);
                          }
                        } 
                        else{
                          Swal({
                            type: response.data.status,
                            title: response.data.message
                          });
                        }
                      })
                      .catch(({response}) => {
                          this.login_errors = response.data.errors;
                        });
            }
          });
        }
			},
      loginWith(provider){
        if (this.$auth.isAuthenticated()) {
          this.$auth.logout();
        }
        this.$auth.authenticate(provider).then(response => {
          if (response.data.status === 'registered'){
            var redirectUrl = this.$route.query.redirect;
            this.$auth.logout();
            this.$store.dispatch('logined',{token: response.data.token,user: response.data.user});

            if (redirectUrl)
              this.$router.push(redirectUrl);

          }
          else
            this.$router.push({name:'register'});
        })
        .catch(e => console.log(e));

      },
			clear(){
				this.login_errors = [];
			}
		}
	}
</script>
