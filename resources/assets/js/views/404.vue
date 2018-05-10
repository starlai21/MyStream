<template>
<div>
<section class="hero is-primary" id="footer">
  <div class="hero-body">
    <div class="container has-text-centered">
      <h1 class="title">
        404
      </h1>
      <h2 class="subtitle">
        This page is not you are looking for.
      </h2>
      <router-link class="button is-primary is-inverted" :to="{name:'root'}">Go Back Home</router-link>
    </div>
  </div>
</section>	
</div>
</template>


<style>
#footer{
  height: 35%;
  overflow: auto;
  margin: auto;
  position: absolute;
  top: 0; left: 0; bottom: 0; right: 0;
}

</style>