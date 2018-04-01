<section class="hero is-primary is-bold">
  <!-- Hero head: will stick at the top -->
  <div class="hero-head">
    <header class="navbar">
      <div class="container">
        <div class="navbar-brand">
          <a class="navbar-item">
            <h1 class= "title is-6">Laohubushimao</h1>
          </a>
          <span class="navbar-burger burger" data-target="navbarMenuHeroC" @click="navToggle">
            <span></span>
            <span></span>
            <span></span>
          </span>
        </div>
        <div id="navbarMenuHeroC" class="navbar-menu" :class="[ navOpen ?  'is-active':'']">
          <div class="navbar-end">

            <router-link to="/" class="navbar-item" exact @click.native="navOpen && navToggle()">
               <span>Home</span>
            </router-link>

            <router-link to="/about" class="navbar-item" @click.native="navOpen && navToggle()">
               <span>About</span>
            </router-link>

            <router-link to="/admin" class="navbar-item" @click.native="navOpen && navToggle()">
               <span>Manage Blog</span>
            </router-link>


            <span class="navbar-item" v-if="!token" v-cloak @click="navOpen && navToggle()">
              <a class="button is-success is-inverted" href="#/login">
                <span class="icon">
                  <i class="fa fa-sign-in"></i>
                </span>
                <span>Login</span>
              </a>
            </span>

            <span class="navbar-item" v-else v-cloak @click="navOpen && navToggle()">
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