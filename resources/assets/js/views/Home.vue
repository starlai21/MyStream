<template>

<div>

  <div  v-if="isLoading" style="  position: fixed;top: 50%;left: 50%;
  transform: translate(-50%, -50%);">
    <span class="icon">
      <i class="fa fa-refresh fa-spin fa-3x fa-fw" style="color:#00d1b2"></i>
    </span>
  </div>




    <div class="tile is-ancestor">
      <div class="tile is-parent is-vertical is-5">
        <template v-for="(post,index) in posts">
          <template v-if="index%2 == 0">
            <post-review :post="post" @tagUpdated="setTag" :tagName="activeTag"></post-review>
          </template>
        </template>
      </div>
      <div class="tile is-parent is-vertical is-5">
        <template v-for="(post,index) in posts">
          <template v-if="index%2 == 1">
            <post-review :post="post" @tagUpdated="setTag" :tagName="activeTag"></post-review>
          </template>
        </template>
      </div>

      <div class="tile is-parent is-vertical">
        <div class="tile is-child box">
            <span class="icon" @click="setDate({year:'',month:''})">
              <i class="fa fa-calendar" style="font-size:20px;color:blue"></i>
            </span>
          <archive @dateUpdated="setDate" @clearDate="setDate" :date="activeDate"></archive>
        </div>

        <div class="tile is-child box">
          <span class="icon" @click="setTag('')">
              <i class="fa fa-tags" style="font-size:20px;color:blue"></i>
          </span>
          <tags @tagUpdated="setTag" @clearTag="setTag" :tags="tags" :tagName="activeTag"></tags>
        </div>
      </div>
    </div>

    <pagination @go="fetchPosts" :pagination="pagination"></pagination>
  </div>

</template>


<style>

</style>


<script>

import Post from '../models/Post.js';
import PostReview from './components/PostReview';
import Archive from './components/Archive';
import Tag from './components/Tag';
import Pagination from './components/Pagination';

    export default {

        data(){
            return{
                isLoading:false,
                posts:[],
                tags:[],
                pagination:{},
                params:{},
                activeTag:"",
                activeDate:{}
            };
        },
        created(){
            this.isLoading = true;

            Post.all(data => {
              this.posts = data.data;
              this.setPagination(data);
              this.isLoading = false;
            },error => {
              this.isLoading = false;
              console.log(error);
            });
            
            Post.tags(data => this.tags = data);
        },
        methods:{
          setPagination(data){
            let pagination = {
                    current_page: data.current_page,
                    last_page: data.last_page,
                    next_page_url: data.next_page_url,
                    prev_page_url: data.prev_page_url,
                    path:data.path
                };
            this.pagination = pagination;
          },
          fetchPosts(pageUrl){
            this.isLoading = true;
            Post.all(data => {
              this.posts = data.data;
              this.setPagination(data);
              this.isLoading = false;
            },error => {
              this.isLoading = false;
              console.log(error);
            },
            pageUrl,this.params);
            this.params = {};
          },
          setDate(date){
            this.params['month'] = date.month;
            this.params['year'] = date.year;
            this.params['tag'] = this.activeTag;
            this.activeDate = date;
            this.fetchPosts();
          },
          setTag(tag){
            this.params['tag'] = tag;
            this.params['month'] = this.activeDate['month'];
            this.params['year'] = this.activeDate['year'];
            this.activeTag = tag;
            this.fetchPosts();
          }

  
        },
        components:{
          'post-review':PostReview,
          'archive': Archive,
          'tags': Tag,
          'pagination': Pagination
        }


    }
    
</script>
