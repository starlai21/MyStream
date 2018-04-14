<template>
  <div>
    <fingerprint-spinner v-if="!result"
      :animation-duration="1500"
      :size="64"
      color="#00d1b2"
      style="position: fixed;top: 50%;left: 50%;
  transform: translate(-50%, -50%);"
    />
    <div class="timeline is-centered" v-if="result">


      <div class="timeline-item">
        <div class="timeline-marker is-primary"></div>
        <div class="timeline-content">
          <p class="heading">Keep moving...</p>
        </div>
      </div>

      <template v-for="year in years">
        <header class="timeline-header">
          <span class="tag is-primary">{{year}}</span>
        </header>
        <div class="timeline-item" v-for="(posts,month) in result[year]">
          <div class="timeline-marker is-primary"></div>
          <div class="timeline-content">
           <p class="heading">{{month}}</p>
            <template v-for="post in posts">
              <router-link :to="{ name: 'post',params:{postId:post.id} }">
                <p>{{post.title}}</p>
              </router-link>              
            </template>

          </div>
        </div>
      </template>

      <header class="timeline-header">
        <span class="tag is-primary">Start</span>
      </header>

    </div>

  </div>
</template>

<style type="text/css">
  .fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
<script>
import Post from '../models/Post.js';
import moment from 'moment';
  export default {
    data(){
      return {
        result: null,
        years: []
      };
    },
    created(){
      Post.all(data => {

        var result = _.groupBy(data,(post) => moment(post.created_at).format('YYYY'));

        _.forEach(
          result,
          (dates, key) => _.update(
            result,
            key,
            posts => _.groupBy(posts, (post) => moment(post.created_at).format('MMMM'))
          )
        );


        this.years = _.keys(result).sort().reverse();
        this.result = result;

        
      },error =>{
        console.log(error);
      },null,{paginate:false});
    },
    filters:{
      postOn(created_at){
        return moment(created_at).format("MMMM YYYY");
      }
    }
  }
</script>
