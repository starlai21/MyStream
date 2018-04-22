<template>

    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="column is-4 is-offset-4">
          <h3 class="title ">Reset Password</h3>
          <!-- <p class="subtitle">A step away from creating your blog.</p> -->
          <div class="box has-text-left">
              <div class="field">
                <label class="label">New Password</label>
                <div class="control has-icons-left has-icons-right">
                    <input type="password" name="password" v-model="password" v-validate="{ is: password_confirmation , min: 6, max:20}" :class="{'input': true, 'is-danger': errors.has('password')}">
                    <span class="icon is-small is-left">
                      <i class="fa fa-lock"></i>
                    </span>
                    <span class="icon is-small is-right">
                      <i v-show="errors.has('password')" class="fa fa-warning"></i>
                    </span>
                    <span v-show="errors.has('password')" class="help is-danger">{{ errors.first('password') }}</span>
                </div>
              </div>
              <div class="field">
                <label class="label">New Password confirmation</label>
                <div class="control has-icons-left has-icons-right">
                    <input class="input" type="password" name="password_confirmation" v-model="password_confirmation" v-validate="{min: 6, max:20}">
                    <span class="icon is-small is-left">
                      <i class="fa fa-lock"></i>
                    </span>
                    <span class="icon is-small is-right">
                      <i v-show="errors.has('password_confirmation')" class="fa fa-warning"></i>
                    </span>
                    <span v-show="errors.has('password_confirmation')" class="help is-danger">{{ errors.first('password_confirmation') }}</span>
                </div>
              </div>

              
              <a class="button is-block is-primary is-fullwidth" @click="reset">Reset</a>
          </div>
          <p>
            <router-link :to="{name:'login'}">Sign In</router-link> &nbsp;·&nbsp;
            <router-link :to="{name:'register'}">Sign Up</router-link> 
          </p>
        </div>
      </div>
    </div>

</template>
<style type="text/css">

.hero.is-success {
/*  background: #FFFACD;*/
}
.hero .nav, .hero.is-success .nav {
  -webkit-box-shadow: none;
  box-shadow: none;
}
.box {
  margin-top: 1rem;
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

import { Validator } from 'vee-validate';

	export default {
    props:['code'],
		data(){
			return{
				password:'',
        password_confirmation:''
			};
		},
		methods:{
			reset(){
        this.$validator.validateAll().then((result) => {
          if (result){
            axios.post('/api/auth/resetPassword',{password: this.password, password_confirmation: this.password_confirmation, password_reset_code: this.code})
                    .then(r => {
                      Swal({
                        type: r.data.status,
                        title: r.data.message
                      });
                      if (r.data.status === 'success'){
                        this.$router.push({name:'login'});  
                      }
                      
                    })
                    .catch(e => {
                      console.log(e);
                      // this.errors.add('email','it is wrong');
                      Swal({
                        type: 'error',
                        title: 'Something went wrong.'
                      });
                    });
          }
          else{
            
          }
        });
			}

		}
	}
</script>
