<template>
<div>
	<fingerprint-spinner v-if="isLoading"
		  :animation-duration="1500"
		  :size="64"
		  color="#00d1b2"
		  style="position: fixed;top: 50%;left: 50%;
  transform: translate(-50%, -50%);"
		/>
	<div class="columns">
		<div class="column">
			<span class="icon"  @click="$router.go(-1)">
				<i class="fa fa-angle-double-left fa-3x"></i>
			</span>
		</div>
	</div>
	<div class="columns" v-if="!isLoading">
	  	<div class="column">
			<div>
				<div class="content ">
				  	<h1 class="has-text-centered">{{post.title}}</h1>

				  	<nav class="level is-mobile">
					  <div class="level-item level-left has-text-centered">
					  	<tag :tags="post.tags"></tag>
					  </div>
					 
					  <div class="level-item level-right has-text-centered">
					  	<span class="tag is-light">{{post.created_at | postOn}}</span>
					  </div>
					</nav>

				  	<div v-html="markDown(post.content)" v-highlight></div>
				</div>
			</div>
		</div>
	</div>
</div>



</template>
<script>
import Post from '../models/Post.js';
import Tag from './components/Tag';
import moment from 'moment';
	export default {
		data(){
			return {
				isLoading: false,
				post:[],
				postId:this.$route.params.postId
			};
		},
		filters:{
			postOn(created_at){
              // return moment(post.created_at).fromNow();
            	return moment(created_at).format("MMMM D, YYYY");
        	}
		},
		methods:{
			markDown(content){
				if(content)
					return marked(content,{sanitize: true});
			}
		},
		created(){
			this.isLoading = true;
			Post.fetchPost(data => {
				this.post = data;
				this.isLoading = false;
			},error => {
              this.isLoading = false;
              console.log(error);
            },this.postId);

		},
		components:{
			'tag':Tag
		}
	}
</script>


