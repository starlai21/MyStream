<section class="hero is-primary is-bold">
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
          <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
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
            <h1 class= "title is-6">Laohubushimao</h1>
          </a>
          <span class="navbar-burger burger" data-target="navbarMenuHeroC" @click="navToggle">
            <span></span>
            <span></span>
            <span></span>
          </span>
        </div>
        <div id="navbarMenuHeroC" class="navbar-menu" :class="[ isNavOpen ?  'is-active':'']">
          <div class="navbar-end">

{{--             <router-link to="/" class="navbar-item" exact @click.native="isNavOpen && navToggle()">
               <span>Home</span>
            </router-link> --}}

{{--             <router-link to="/about" class="navbar-item" @click.native="isNavOpen && navToggle()">
               <span>About</span>
            </router-link> --}}

            <router-link to="/admin" class="navbar-item" @click.native="isNavOpen && navToggle()">
               <span>Manage Blog</span>
            </router-link>


{{--             <span class="navbar-item" v-if="!token" v-cloak @click="isNavOpen && navToggle()">
              <a class="button is-success is-inverted" href="#/login">
                <span class="icon">
                  <i class="fa fa-sign-in"></i>
                </span>
                <span>Login</span>
              </a>
            </span> --}}

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
        My Stream
      </h1>
      <h2 class="subtitle">
        {{$quote}}
      </h2>
    </div>
  </div>

  <!-- Hero footer: will stick at the bottom -->
  @include('layouts.nav')

</section>