<template>

    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="column is-6 is-offset-3">
          <h3 class="title ">My Stream - Sign Up</h3>
          <p class="subtitle">A step away from creating your blog.</p>
          <div class="box has-text-left" v-show="!authenticated">
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
                    <input type="text" placeholder="blog uri: /blog/username" name="userName" v-model="userName" v-validate="'required|alpha_dash|username_unique'" data-vv-delay="1000" :class="{'input': true, 'is-danger': errors.has('userName'), 'is-success': fields.userName && fields.userName.valid }">
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

              
              <a class="button is-block is-primary is-fullwidth" @click="register">Sign up</a>
              <div class="has-text-centered">or</div>
              <a class="button is-block is-dark is-fullwidth" @click="registerWith('github')">
                <span class="icon is-left"><i class="fa fa-github"></i></span>&nbsp
                Sign up with github
              </a>
          </div>
          <div class="box has-text-left" v-show="authenticated">
            <div class="field">
                <label class="label">Username</label>
                <div class="control has-icons-left has-icons-right">
                    <input type="text" placeholder="blog uri: /blog/username" name="userName" v-model="userName" v-validate="'required|alpha_dash|username_unique'" data-vv-delay="1000" :class="{'input': true, 'is-danger': errors.has('userName'), 'is-success': fields.userName && fields.userName.valid }">
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
            <a class="button is-block is-primary is-fullwidth" @click="authRegister('github')">Submit</a>
          </div>


          <p>
            <router-link :to="{name:'login'}">Sign in</router-link> &nbsp;·&nbsp;
            <router-link :to="{name:'forgotPassword'}">Forgot password</router-link> 
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
		data(){
			return{
				email:'',
				password:'',
        password_confirmation:'',
        userName:'',
        token: null,
        authenticated: false
			};
		},
    components:{
      // VeeValidate
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
			},
      registerWith(provider){
        if (this.$auth.isAuthenticated()) {
          this.$auth.logout();
        }
        this.$auth.authenticate(provider).then(r => {
          this.authenticated = true;
        })
        .catch(e => console.log(e));

      },
      authRegister(provider){
        this.$validator.validate('userName',this.userName).then((result) => {
          if (result){
            axios.post('/api/auth/authRegister',{name: this.userName, provider: provider, access_token: this.$auth.getToken()})
                    .then(response => {
                      if (response.data.status === 'success'){
                        // this.$auth.logout();
                        this.$store.dispatch('logined',{token: response.data.token, userName: this.userName});
                      }
                      Swal({
                        type: response.data.status,
                        title: response.data.message
                      });
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

      this.authenticated = this.$auth.isAuthenticated();
 
    }
	}
</script>
