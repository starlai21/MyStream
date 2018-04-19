<template>
<div>
  <blog-header></blog-header>
  <section class="section">
    <div class="container body">

      <fingerprint-spinner v-if="isLoading"
          :animation-duration="1500"
          :size="64"
          color="#00d1b2"
          style="position: fixed;top: 50%;left: 50%;
      transform: translate(-50%, -50%);"
        />
    <div class="columns">
      <div class="column is-10">
        <div class="tile is-ancestor">
          <div class="tile is-parent is-vertical is-5">
            <template v-for="(post,index) in posts">
              <template v-if="index%2 == 0">
                <post-review :userName="userName" :post="post" @tagUpdated="setTag" :tagName="activeTag"></post-review>
              </template>
            </template>
          </div>
          <div class="tile is-parent is-vertical is-5">
            <template v-for="(post,index) in posts">
              <template v-if="index%2 == 1">
                <post-review :userName="userName" :post="post" @tagUpdated="setTag" :tagName="activeTag"></post-review>
              </template>
            </template>
          </div>
        </div>
      </div>
      <div class="column is-auto">
        <div class="tile is-ancestor">
        <div class="tile is-parent is-vertical">
          <div class="tile is-child box">
              <span class="icon" @click="setDate({year:'',month:''})">
                <i class="fa fa-calendar" style="font-size:20px;color:blue"></i>
              </span>
            <archive :userName="userName" @dateUpdated="setDate" @clearDate="setDate" :date="activeDate"></archive>
          </div>

          <div class="tile is-child box">
            <span class="icon" @click="setTag('')">
                <i class="fa fa-tags" style="font-size:20px;color:blue"></i>
            </span>
            <tags @tagUpdated="setTag" @clearTag="setTag" :tags="tags" :tagName="activeTag"></tags>
          </div>
        </div>
      </div>
    </div>
  </div>


      <pagination @go="updatePosts" :pagination="pagination" v-show="!isLoading"></pagination>
    </div>
  </section>
  <blog-footer></blog-footer>
</div>
</template>


<style>

</style>


<script>

import Header from './Header';
import PostReview from '../components/PostReview';
import PostsMixin from '../../mixins/PostsMixin.js';
import {mapState} from 'vuex';

  export default {
    data(){
      return {
        
      };
    },
    created(){

    },
    mixins: [PostsMixin],
    components:{
        'post-review': PostReview,
        'blog-header': Header
      },
    computed: mapState({
      userName: state => state.tempUserName,
      blog: state => state.blog
     }),
    watch:{
      userName(newUserName, oldUserName){
        this.params['userName'] = newUserName;
        this.updateAll();
      }
    }
  }
    
</script>
