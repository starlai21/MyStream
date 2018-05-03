<template>
  <div>
    <blog-header></blog-header>
    <section class="section">
      <div class="container body">
        <fingerprint-spinner v-if="!result"
          :animation-duration="1500"
          :size="64"
          color="#00d1b2"
          style="position: fixed;top: 50%;left: 50%;
      transform: translate(-50%, -50%);"
        />
        <div class="timeline is-centered" v-if="result">


          <div class="timeline-item">
          <div class="timeline-marker is-black" :class="filterColor(blog.color)"></div>
          <div class="timeline-content">
            <p class="heading">Keep moving...</p>
          </div>
          </div>

          <template v-for="year in years">
            <header class="timeline-header">
              <span class="tag is-black" :class="filterColor(blog.color)">{{year}}</span>
            </header>
            <div class="timeline-item" v-for="(posts,month) in result[year]">
              <div class="timeline-marker is-black" :class="filterColor(blog.color)"></div>
              <div class="timeline-content">
               <p class="heading">{{month}}</p>
                <template v-for="post in posts">
                  
                  <!-- <span class="tag is-light"> -->
                    <router-link class="is-size-6-mobile" :class="getTextColor(filterColor(blog.color))" :to="{ name: 'post',params:{postId:post.id, userName: userName} }">{{post.title}}</router-link> 
                  <br>
<!--                   <router-link :to="{ name: 'post',params:{postId:post.id} }">
                    {{post.title}}
                  </router-link>  -->             
                </template>

              </div>
            </div>
          </template>

            <header class="timeline-header">
            <span class="tag is-black" :class="filterColor(blog.color)">Start</span>
          </header>
        </div>
      </div>
    </section>
    <blog-footer></blog-footer>
  </div>
</template>


<script>
import Header from './Header';
import Post from '../../models/Post.js';
import moment from 'moment';
import {mapState} from 'vuex';
  export default {
    data(){
      return {
        result: null,
        years: []
      };
    },
    components:{
      'blog-header': Header
    },
    created(){
      this.updateAll();
    },
    filters:{
      postOn(created_at){
        return moment(created_at).format("MMMM YYYY");
      }
    },
    methods:{
      updatePosts(){
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
        },null,{pagination:false,userName: this.userName});
      },
      updateAll(){
        this.updatePosts();
      },
      getTextColor(color){
        return "has-text-"+color.split('-')[1];
      },
      filterColor(color){
        if (color === "is-light")
          return "is-dark";
        else
          return color;
      }
    },
    watch:{
      'userName': 'updateAll',
      '$store.state.notify': 'updateAll'
    },
    computed: mapState({
      userName: state => state.tempUserName,
      blog: state => state.blog
     })
  }
</script>
