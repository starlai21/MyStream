<template>

    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="column is-4 is-offset-4">
          <h3 class="title">My Stream - Login</h3>
          <p class="subtitle">Please login to proceed.</p>
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
                   <input name="password" type="password" placeholder="Password" v-model="password" @input = "clear" required  v-validate="{min: 6, max:20}" data-vv-delay="1000" :class="{'input': true, 'is-danger': errors.has('password')}">
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
              <div class="field">
              </div>
              <a class="button is-block is-primary is-fullwidth" @click="login">Login</a>
          </div>
          <p>
            <router-link :to="{name:'register'}">Sign Up</router-link> &nbsp;·&nbsp;
            <a href="../">Forgot Password</a> 
            <!-- <a href="../">Need Help?</a> -->
          </p>
        </div>
      </div>
    </div>

</template>
<style type="text/css">

.hero.is-success {
/*  background: #F2F6FA;*/
}
.hero .nav, .hero.is-success .nav {
  -webkit-box-shadow: none;
  box-shadow: none;
}
.box {

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
import VeeValidate from 'vee-validate';
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
                        if (response.data.status === 'success')
                          this.$store.dispatch('logined',{token: response.data.token,userName: response.data.userName});
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
			clear(){
				this.login_errors = [];
			}
		},
    components:{
      VeeValidate
    }
	}
</script>
