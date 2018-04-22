<template>
  <section class="hero is-info is-fullheight">
    <div class="hero-head">
      <nav class="navbar">
        <div class="container">
          <div class="navbar-brand">
            <a class="navbar-item" href="../">
              MY STREAM
            </a>
            <span class="navbar-burger burger" data-target="navbarMenu">
              <span></span>
              <span></span>
              <span></span>
            </span>
          </div>
          <div id="navbarMenu" class="navbar-menu">
              <div class="navbar-end">
                <span class="navbar-item">
                	<router-link :to="{name:'root'}" class="button is-white is-outlined">
                  	<span class="icon">
                    	<i class="fa fa-home"></i>
                  	</span>
                  	<span>Home</span>
                	</router-link>
              	</span>
                <span class="navbar-item">
                	<a class="button is-white is-outlined" href="/blog/laravel">
                  	<span class="icon">
                    	<i class="fa fa-superpowers"></i>
                  	</span>
                  	<span>Example</span>
                	</a>
              	</span>
            	<span class="navbar-item">
            	  <router-link :to="{name:'login'}" class="button is-white is-outlined">
            	    <span class="icon">
            	      <i class="fa fa-sign-in"></i>
            	    </span>
            	    <span>Sign In</span>
            	  </router-link>
            	</span>
            	<span class="navbar-item">
            	  <a class="button is-white is-outlined" href="https://github.com/starlai21/MyStream">
            	    <span class="icon">
            	      <i class="fa fa-github"></i>
            	    </span>
            	    <span>View Source</span>
            	  </a>
            	</span>
          	  </div>
          </div>
      	</div>
      </nav>
    </div>
      
        <router-view></router-view>
      

  </section>

</template>
<style type="text/css">
	.hero.is-info {
/*	  background: linear-gradient(
	      rgba(0, 0, 0, 0.5),
	      rgba(0, 0, 0, 0.5)
	    ), url('https://unsplash.it/1200/900?random') no-repeat center center fixed;
	  -webkit-background-size: cover;
	  -moz-background-size: cover;
	  -o-background-size: cover;
	  background-size: cover;*/
	}
	.hero .nav, .hero.is-success .nav {
	  -webkit-box-shadow: none;
	  box-shadow: none;
	}
	.hero .subtitle {
	  padding: 3rem 0;
	  line-height: 1.5;
	}

</style>
<script type="text/javascript">



export default {
	mounted(){
		var burger = $('.burger');
		var menu = $('#'+burger.data('target'));
		burger.on('click', function() {
		  burger.toggleClass('is-active');
		  menu.toggleClass('is-active');
		});
    var items = $('.navbar-item');
    items.on('click', function(){
      burger.toggleClass('is-active');
      menu.toggleClass('is-active');
    })

	}
}

</script>