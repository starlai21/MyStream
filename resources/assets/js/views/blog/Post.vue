<template>
<div>
	<blog-header></blog-header>
	<section class="section">
		<div class="container body">
			<fingerprint-spinner v-show="isLoading"
		  		:animation-duration="1500"
		  		:size="64"
		  		color="#00d1b2"
		  		style="position: fixed;top: 50%;left: 50%;
  				transform: translate(-50%, -50%);"
			/>
			<back-to-top text="Back to top" visibleOffset="800">
				<a class="button is-dark">Back to Top</a>
			</back-to-top>
			<div class="columns">
				<div class="column">
					<span class="icon"  @click="$router.go(-1)">
						<i class="fa fa-angle-double-left fa-3x"></i>
					</span>
				</div>
			</div>
			<div class="columns" v-show="!isLoading">
			  	<div class="column is-8">
					<div>
						<div class="content" id="example-content">
						  	<h1 class="has-text-centered">{{post.title}}</h1>
		
						  	<nav class="level">
							  <div class="level-left">
							  	<div class="level-item">
							  		<tag :tags="post.tags"></tag>	
							  	</div>
							  </div>
							 
							  <div class="level-right">
							  	<div class="level-item">
							  		<span class="tag is-light">{{post.created_at | postOn}}</span>
							  		<span class="tag is-primary is-capitalized">{{post.user && post.user.name}}</span>
							  	</div>
							  </div>
		
							</nav>
							<hr>
							<vue-markdown v-highlight :source="content"  :toc="true" toc-class="menu-list" toc-id="table" @toc-rendered="processAnchors" :toc-first-level="1" :toc-last-level="3"
							 ></vue-markdown>
							
						</div>
						<hr>
						<!-- previous post & next post -->
					  	<nav class="level">
						  <div class="level-left">
						  	<div class="level-item">
							  	<router-link v-if="prev" :to="{ name: 'post',params:{postId: prev.id, userName: userName} }">
					    	    	< {{prev.title}}
					    	  	</router-link>
						  	</div>
						  </div>
						  <div class="level-right">
						  	<div class="level-item">
							  	<router-link v-if="next" :to="{ name: 'post',params:{postId: next.id, userName: userName} }">
					    	    	{{next.title}} >
					    	  	</router-link>
						  	</div>
						  </div>
						</nav>
						<hr v-if="next||prev">
						<!-- comments begin -->
						<div id="comments">
							<article class="media" v-for="(comment, index) in comments">
							  <figure class="media-left is-hidden-mobile">
								  	<p class="image is-64x64">
								      <img class="is-circular" src="https://unsplash.it/64/64?random">
								    </p>
							  </figure>
							  <div class="media-content">
							    <div class="content">
							      <p>
							      	<!-- user name -->
							        <strong class="is-capitalized">
							        	{{comment.user.name}}
							        </strong>
							        <span v-if="comment.user.name === userName" class="tag is-primary">author</span>
							        &nbsp 
							        <!-- created_at -->
							         <span class="has-text-grey">
							         	{{comment.created_at | postAgo}}
							         </span>
							        <br>
							        <!-- comment content -->
							        <vue-markdown v-highlight :source="comment.content"></vue-markdown>
							        <small>
							        	<!-- like button -->
							        	<a @click="toggleLike(comment.id,index)"><i v-if="likes[index]" class="fa fa-thumbs-up" style="font-size:18px;"></i>
							        		<i v-else class="fa fa-thumbs-o-up" style="font-size:18px;"></i>
							        	{{comment.likes_count | formatLikes}}
							        	</a> · 
							        	<!-- reply -->
							        	<a @click="handleReply(comment.id, index, comment.replies_count)">Reply{{comment.replies_count| formatReplies}}</a>
							        </small>
							      </p>
							    </div>

							    <!-- replies -->
						        <article class="media">
							      <figure class="media-left is-hidden-mobile">
							        <p class="image is-48x48">
							          <img class="is-circular" src="https://bulma.io/images/placeholders/96x96.png">
							        </p>
							      </figure>
								    <div class="media-content">
								        <div class="content">
								          <p>
								            <strong>Sean Brown</strong> &nbsp<span class="has-text-grey"> in 2 hrs</span>
								            <br>
								            Donec sollicitudin urna eget eros malesuada sagittis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Aliquam blandit nisl a nulla sagittis, a lobortis leo feugiat.
								            <br>
								            <small><a>Like</a> · <a>Reply</a></small>
								          </p>
								        </div>
								    </div>
								</article>
								<!-- replies info & make a reply button -->
						        <article class="media">
								    <div class="media-content">
								        <div class="content"> 	
											There are 3 more replies, <a>click to view</a>
								        </div>
								    </div>
								    <div class="media-right">
								    	<div v-show="isLogined">
									  		<button  class="button" >Make a reply</button>
										</div>
									  <div v-show="!isLogined">
									  	<router-link :to="{name:'register', query: {redirect: $route.fullPath}}">create an account </router-link>
									  </div>
									</div>
								</article>

<!-- 								<article class="media" id="postComment" v-if="isLogined &&" >
								  <figure class="media-left is-hidden-mobile">
								    <p class="image is-64x64">
								      <img class="is-circular" src="https://unsplash.it/64/64?random">
								    </p>
								  </figure>
								  <div class="media-content">
								    <div class="field">
								      <p class="control">
								        <textarea class="textarea" v-model="comment_content" placeholder="Add a comment..."></textarea>
								      </p>
								    </div>
								    <div class="field">
								      <p class="control">
								        <button class="button" @click.prevent="postNewComment" :disabled="!comment_content">Post comment</button>
								      </p>
								    </div>
								  </div>
								</article> -->


																



							  </div>
							  
							</article>
							<!-- pagination -->
							<div v-show="comments.length > 0">
								<hr>
									<pagination @go="fetchComments" :pagination="pagination" v-show="!isLoading"></pagination>
								<hr>
							</div>
						
							<article class="media" id="postComment" v-if="isLogined">
							  <figure class="media-left is-hidden-mobile">
							    <p class="image is-64x64">
							      <img class="is-circular" src="https://unsplash.it/64/64?random">
							    </p>
							  </figure>
							  <div class="media-content">
							    <div class="field">
							      <p class="control">
							        <textarea class="textarea" v-model="comment_content" placeholder="Add a comment..."></textarea>
							      </p>
							    </div>
							    <div class="field">
							      <p class="control">
							        <button class="button" @click.prevent="postNewComment" :disabled="!comment_content">Post comment</button>
							      </p>
							    </div>
							  </div>
							</article>
							<article class="media" v-else>
								<div class="media-content">
									<div class="content">
							Please<router-link :to="{name:'login', query: {redirect: $route.fullPath}}"> log in </router-link>or
								<router-link :to="{name:'register', query: {redirect: $route.fullPath}}">create an account </router-link>to participate in this conversation.
									</div>
							</div>
							</article>

						</div>

						
					</div>
				</div>
				<div class="column is-3 is-offset-1 is-hidden-mobile" v-show="!isLoading">
					<affix relative-element-selector="#example-content" style="width: 300px" >
						<div id="toc">
							<p class="menu-label">Table of Contents</p>
							<div id="table"></div>				
						</div>
					</affix>
				</div>
			</div>
		</div>
	</section>
	<blog-footer></blog-footer>
</div>

</template>
<style>
.is-circular {
    border-radius: 50%;
}
.is-author {

}

</style>

<script>
import Post from '../../models/Post.js';
import Tag from '../components/Tag';
import moment from 'moment';
import VueMarkdown from 'vue-markdown';
import BackToTop from 'vue-backtotop';
import Header from './Header';
import {mapState} from 'vuex';
import Pagination from '../components/Pagination';

	export default {
		props:['postId','userName'],
		data(){
			return {
				isLoading: false,
				post: [],
				prev: null,
				next: null,
				content: '',
				comments: [],
				likes: [],
				comment_content: '',
				pagination: {}
			};
		},
		computed: mapState({
    		isLogined: state => state.isLogined
    	}),
		filters:{
			postOn(created_at){
            	return moment(created_at).format("MMMM D, YYYY");
        	},

        	postAgo(created_at){
        		return moment(created_at).fromNow();
        	},
        	formatLikes(number){
        		if (number === 0)
        			return '';
        		return number;
        	},
        	formatReplies(number){
        		if (number === 0)
        			return '';
        		return "(" + number + ")";
        	}
		},
		methods:{
			handleReply(comment_id, index, replies_count){
				if (replies_count === 0){
					//open reply div
				}
				else {
					//fetch replies of this comment
					//open reply div
				}
			},
			toggleLike(comment_id, index){
				if (this.isLogined){
					axios.post("/api/comment/toggleLike",{comment_id: comment_id})
							.then(r => {
								var state = this.likes[index];
								Vue.set(this.likes, index, !state);
								var comment = this.comments[index];
								if (state){
									comment.likes_count--;
									Vue.set(this.comments, index, comment);
								}
								else{
									comment.likes_count++;
									Vue.set(this.comments, index, comment);
								}
							})
							.catch(e => {
								console.log(e);
							})
				}
				else {
					Swal({
						type: 'error',
						title: 'Please log in first!'
					});
				}
			},
		  	setPagination(data){
		  	  let pagination = {
		  	          current_page: data.current_page,
		  	          last_page: data.last_page,
		  	          last_page_url: data.last_page_url, 
		  	          next_page_url: data.next_page_url,
		  	          prev_page_url: data.prev_page_url,
		  	          path:data.path
		  	      };
		  	  this.pagination = pagination;
		  	},
			postNewComment(){
				axios.post("/api/comment/store",{content: this.comment_content, post_id: this.post.id})
						.then(response => {
							if (response.data.status === 'success'){
								this.fetchComments(this.pagination.last_page_url);
								// Vue.set(this.comments, this.comments.length, response.data.comment);
								this.comment_content = '';
							}
	                    	Swal({
	                    	  type: response.data.status,
	                    	  title: response.data.message
	                    	});


						})
						.catch(e => {
							console.log(e);
							Swal({
	                    		type: "error",
	                    		title: "Woops, something wen wrong!"
	                    	});
						});
			},
			fetchComments(pageUrl){
				this.isLoading = true;
				Post.fetchComments(data => {
					this.isLoading = false;
					this.comments = data.comments.data;
					this.likes = data.likes;
					this.setPagination(data.comments);
				},error => {
					this.isLoading = false;
					console.log(error);
				},pageUrl, {post_id: this.postId});
			},
			update(){
				if (this.postId){
					$('#table').html('');
					this.isLoading = true;
					Post.fetchPost(data => {
						this.post = data.cur;
						this.content = this.post.content;
						this.prev = data.prev;
						this.next = data.next;
						this.isLoading = false;
					},error => {
		              this.isLoading = false;
		              console.log(error);
		              this.$router.push({name:'blog_home', params: {userName: this.userName}});
		            },{post_id: this.postId, userName: this.userName});

		            this.fetchComments(null);
				}
			},
			processAnchors(){

				$("#table li a[href^='#']").on('click', (e) => {
					e.preventDefault();
					var $id = $($(e.target).attr('href'));
					var pos = $id.offset().top;
				    // animated top scrolling
				    $('body, html').animate({scrollTop: pos});

				});

				Vue.nextTick().then( () => {
					$("a.toc-anchor").on('click', (e) => {
						e.preventDefault();
						var $id = $(e.target.parentNode.parentNode);
						var pos = $id.offset().top;
				    	// animated top scrolling
				    	$('body, html').animate({scrollTop: pos});
					});
					


					var segs = [];
					$("a.toc-anchor").each(function(index,node){
						segs.push(node.parentNode);
						});

					$(window).bind('scroll',function(){

						var scrollTop = $(this).scrollTop();
						var topSeg = null
                    	for (var idx in segs) {
                    	    var seg = segs[idx]
                    	    if (seg.offsetTop > scrollTop) {
                    	        continue
                    	    }
                    	    if (!topSeg) {
                    	        topSeg = seg
                    	    } else if (seg.offsetTop >= topSeg.offsetTop) {
                    	        topSeg = seg
                    	    }
                    	}
                    	if (topSeg) {
                    	    $("#table li a[href^='#']").removeClass("is-active")
                    	    var link = "#" + $(topSeg).attr("id")
                    	    $('#table li a[href="' + link + '"]').addClass("is-active")
                    	}
					});

				});
			}
		},
		created(){
			this.update();
		},
		components:{
			'tag':Tag,
			VueMarkdown,
			BackToTop,
			'blog-header': Header,
			'pagination': Pagination
		},
		watch:{
			'postId':'update'
		},
		mounted(){

		}
	}
</script>


