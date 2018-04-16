<div class="hero-foot">
  <nav class="tabs is-boxed is-centered">
    <div class="container">
      <ul>
        <router-link tag="li" :to="{ name: 'blog_home', params: { userName: this.$route.params.userName }}">
          <a>Home</a>
        </router-link>  

        <router-link tag="li" :to="{ name: 'archives', params: { userName: this.$route.params.userName }}">
          <a>Archives</a>
        </router-link>
      </ul>
    </div>
  </nav>
</div>