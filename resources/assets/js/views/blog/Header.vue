<template>
	<section class="hero is-primary is-bold" :class="blog.color">
  <!-- Hero head: will stick at the top -->
  <div class="modal" :class="{'is-active': isAboutOpen}">
    <div class="modal-background" @click="isAboutOpen = !isAboutOpen"></div>
    <div class="modal-content">
         <div class="card">
      <div class="card-content">
        <div class="media">
          <div class="media-left">
            <figure class="image is-48x48">
               <img :src="'/images/avatar.jpg'" alt="Placeholder image">
            </figure>
          </div>
          <div class="media-content has-text-black">
            <p class="is-4">老虎不是猫</p>
            <p class="is-6">Junjie.Chen@dal.ca</p>
          </div>
        </div>

        <div class="content">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit.
          Phasellus nec iaculis mauris. <a>@bulmaio</a>.
          <a href="#">#css</a> <a href="#">#responsive</a>
          <br>
        </div>
      </div>
    </div>
    </div>
  </div>
  <div class="hero-head">
    <header class="navbar">
      <div class="container">
        <div class="navbar-brand">
          <a class="navbar-item" @click="isAboutOpen = !isAboutOpen">
            <h1 class= "title is-6">{{userName}}</h1>
          </a>
          <span class="navbar-burger burger" data-target="navbarMenuHeroC" @click="navToggle">
            <span></span>
            <span></span>
            <span></span>
          </span>
        </div>
        <div id="navbarMenuHeroC" class="navbar-menu" :class="[ isNavOpen ?  'is-active':'']">
          <div class="navbar-end">


            <router-link v-if="token" :to="{ name: 'admin', params: { userName: loginedUserName }}" class="navbar-item" @click.native="isNavOpen && navToggle()">
               <span>Manage Blog</span>
            </router-link>
            <router-link v-else :to="{ name: 'login'}" class="navbar-item" @click.native="isNavOpen && navToggle()">
               <span>Login</span>
            </router-link>



            <span class="navbar-item" v-if="token" v-cloak @click="isNavOpen && navToggle()">
              <a class="button is-danger is-inverted" @click="logout">
                <span class="icon">
                  <i class="fa fa-sign-out"></i>
                </span>
                <span>Logout</span>
              </a>
            </span>

          </div>
        </div>
      </div>
    </header>
  </div>

  <!-- Hero content: will be in the middle -->
  <div class="hero-body">
    <div class="container has-text-centered">
      <h1 class="title">
        {{blog.name}}
      </h1>
      <h2 class="subtitle">
      	{{blog.slogan}}
      </h2>
    </div>
  </div>

  <!-- Hero footer: will stick at the bottom -->

  <div class="hero-foot">
  <nav class="tabs is-boxed is-centered">
    <div class="container">
      <ul>
<!--         <router-link tag="li" :to="{ name: 'blog_home', params: { userName: this.$route.params.userName }}" exact>
          <a>Home</a>
        </router-link>   -->
        <router-link tag="li" :to="{ name: 'blog_home', params: { userName: userName }}" exact>
          <a>Home</a>
        </router-link>  

<!--         <router-link tag="li" :to="{ name: 'archives', params: { userName: this.$route.params.userName }}">
          <a>Archives</a>
        </router-link> -->

        <router-link tag="li" :to="{ name: 'archives', params: { userName: userName }}">
          <a>Archives</a>
        </router-link>

        
      </ul>
    </div>
  </nav>
</div>

</section>
</template>
<script type="text/javascript">
import {mapState} from 'vuex';
	export default {
		data(){
			return {
				isNavOpen:false,
        isAboutOpen:false
			};
		},
    created(){

    },
   	methods:{
    	navToggle(){
    		this.isNavOpen = !this.isNavOpen;
    	},
    	logout(){
        this.$store.dispatch('logout');
    	}
    },
    computed: mapState({
      token: state => state.token,
      loginedUserName: state => state.userName,
      userName: state => state.tempUserName,
      blog: state => state.blog
   	 }),
    watch:{
      userName(n,o){
        console.log("new user name "+n);
        console.log("old user name "+o);
      }
    }
	}
</script>