<template>

    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="column is-6 is-offset-3">
          <h3 class="title ">My Stream - Sign Up</h3>
          <p class="subtitle">A step away from creating your blog.</p>
          <div class="box has-text-left">
              <div class="field">
                <label class="label">Email</label>
                <div class="control has-icons-left has-icons-right">
                    <input type="text"  name="email" v-model="email" v-validate="'required|email|email_unique'" data-vv-delay="1000" :class="{'input': true, 'is-danger': errors.has('email'), 'is-success': fields.email && fields.email.valid }">
                    <span class="icon is-small is-left">
                      <i class="fa fa-envelope"></i>
                    </span>
                    <span class="icon is-small is-right">
                      <i v-show="errors.has('email')" class="fa fa-warning"></i>
                    </span>
                 
                    <span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>
                    <span v-show="!errors.has('email') && fields.email && fields.email.valid" class="help is-success">I'm available.</span>
                </div>
              </div>
              <div class="field">
                <label class="label">Username</label>
                <div class="control has-icons-left has-icons-right">
                    <input type="text" placeholder="It cannot be changed." name="userName" v-model="userName" v-validate="'required|alpha_dash|username_unique'" data-vv-delay="1000" :class="{'input': true, 'is-danger': errors.has('userName'), 'is-success': fields.userName && fields.userName.valid }">
                    <span class="icon is-small is-left">
                      <i class="fa fa-user"></i>
                    </span>
                    <span class="icon is-small is-right">
                      <i v-show="errors.has('userName')" class="fa fa-warning"></i>
                    </span>
                    
                    <span v-show="errors.has('userName')" class="help is-danger">{{ errors.first('userName') }}</span>
                    <span v-show="!errors.has('userName') && fields.userName && fields.userName.valid" class="help is-success">I'm available.</span>
                </div>
              </div>
              <div class="field">
                <label class="label">Password</label>
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
                <label class="label">Password confirmation</label>
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

              
              <a class="button is-block is-primary is-fullwidth" @click="register">Sign Up</a>
          </div>
          <p>
            <router-link :to="{name:'login'}">Login</router-link> &nbsp;·&nbsp;
            <a href="../">Forgot Password</a> 
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
import VeeValidate from 'vee-validate';
import { Validator } from 'vee-validate';

	export default {
		data(){
			return{
				email:'',
				password:'',
        password_confirmation:'',
        userName:''
			};
		},
    components:{
      VeeValidate
    },
		methods:{
			register(){
        this.$validator.validateAll().then((result) => {
          if (result){
            axios.post('/api/auth/register',{email: this.email, password: this.password, password_confirmation: this.password_confirmation, name: this.userName})
                    .then(r => {
                      Swal({
                        type:'success',
                        title:'The activation link has been sent to your email.'
                      });
                      this.$router.push({name:'login'});
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

		},
    created(){
      Validator.extend('email_unique', {
        getMessage: field => `The ${field} has been taken.`,
        validate: value => axios.post('/api/auth/checkEmail',{email:value})
                                  .then(r => {
                                    return {
                                      valid: r.data.valid
                                    };
                                  })
      });
      Validator.extend('username_unique', {
        getMessage: field => `The ${field} has been taken.`,
        validate: value => axios.post('/api/auth/checkUserName',{userName:value})
                                  .then(r => {
                                    return {
                                      valid: r.data.valid
                                    };
                                  })
      });
    }
	}
</script>
