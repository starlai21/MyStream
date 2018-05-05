<template>
    <div class="hero-body">
      <div class="container has-text-centered">
        <div class="column is-4 is-offset-4">
          <h3 class="title">Forgot Password</h3>
          <p class="subtitle">Please enter your email.</p>
          <div class="box">
              <div class="field">
                <div class="control has-icons-left has-icons-right">
                  	<input name="email" type="email" placeholder="Email" v-model="email" v-validate="'required|email'" data-vv-delay="1000" :class="{'input': true, 'is-danger': errors.has('email')}">
				    <span class="icon is-small is-left">
				        <i class="fa fa-envelope"></i>
				    </span>
                    <span class="icon is-small is-right">
                      <i v-show="errors.has('email')" class="fa fa-warning"></i>
                    </span>
                    <span v-show="errors.has('email')" class="help is-danger">{{ errors.first('email') }}</span>

                </div>
              </div>

              <a class="button is-block is-primary is-fullwidth" @click="submit">Submit</a>
          </div>
          <p>
            <router-link :to="{name:'register'}">Sign up</router-link> &nbsp;·&nbsp;
            <router-link :to="{name:'login'}">Sign in</router-link>
            
          </p>
        </div>
      </div>
    </div>
</template>
<script>
	export default {
		data(){
			return{
				email:'',
			};
		},
		methods:{
			submit(){
		        this.$validator.validateAll().then((result) => {
		          if (result){
		            axios.post('/api/auth/forgotPassword',{email: this.email})
		                    .then(r => {
		        				if (r.data.status === 'error'){
		        					this.errors.add('email', r.data.message);
		        				}
		                      	Swal({
		                        	type: r.data.status,
		                        	title: r.data.message
		                      	});
		                      //this.$router.push({name:'login'});
		                    })
		                    .catch(e => {
		                      console.log(e);
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