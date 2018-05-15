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
			<back-to-top text="Back to top" visibleOffset="1000">
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
								      <img class="is-circular" :src="comment.user.avatar_url">
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
							        	<a v-if="comment.replies_count === 0" @click="makeReply(index,null,$event)"><i class="fa fa-comment-o" style="font-size:18px"></i></i></a>
							        	<a v-else @click="toggleReplies(index,comment.id)">
							        		<template v-if="comment.toggle">
							        			Collapse replies
							        		</template>
							        		<template v-else>
							        			<i class="fa fa-comments-o" style="font-size:20px"></i> ({{comment.replies_count}})
							        		</template>
							        		
							        	</a>
							        </small>
							      </p>
							    </div>

							    <!-- reply input textarea -->
							    <template v-if="comment.replies_count === 0">
									<transition mode="in-out">
										<article class="media"  v-if="selectedCommentIndex === index" >
										  <figure class="media-left is-hidden-mobile">
										    <p class="image is-64x64">
										      <img class="is-circular" :src="user.avatar_url">
										    </p>
										  </figure>
										  <div class="media-content">
										    <div class="field">
										      <p class="control">
										        <textarea class="textarea" v-model="reply_content" placeholder="Add a reply..."></textarea>
										      </p>
										    </div>
										    <div class="field">
										      <p class="control">
										        <button class="button" @click.prevent="postNewReply(comment.id,index)" :disabled="!reply_content">Post reply</button>
										      </p>
										    </div>
										  </div>
										</article>
									</transition>
								 </template>


							    <!-- replies -->
							    <transition-group mode="in-out">
							    <template v-if="comment.replies_count > 0 && comment.toggle">
							    	
						        	<article class="media" v-for="(reply, replyIndex) in comment.replies.slice(0,show_replies)" :key="reply.id">
								      <figure class="media-left is-hidden-mobile">
								        <p class="image is-48x48">
								          <img class="is-circular" :src="reply.user.avatar_url">
								        </p>
								      </figure>
									    <div class="media-content">
									        <div class="content">
									          <p>
									            <strong class="is-capitalized">{{reply.user.name}}</strong> <span v-if="reply.user.name === userName" class="tag is-primary">author</span>
									             &nbsp<span class="has-text-grey"> {{reply.created_at | postAgo}}</span>
									            <br>
									            <vue-markdown v-highlight :source="reply.content"></vue-markdown>
									            
									            <small>
									            	<a @click="toggleLike(reply.id,index,replyIndex)"><i v-if="reply.like" class="fa fa-thumbs-up" style="font-size:18px;"></i>
							        				<i v-else class="fa fa-thumbs-o-up" style="font-size:18px;"></i>
							        				{{reply.likes_count | formatLikes}}
							        				</a> · <a @click="makeReply(index, reply.user.name,$event)">Reply</a>
							        			</small>
									          </p>
									        </div>
									    </div>
									</article>
						        	<article class="media" v-if="comment.loadMore" v-for="(reply, replyIndex) in comment.replies.slice(show_replies)" :key="reply.id">
								      <figure class="media-left is-hidden-mobile">
								        <p class="image is-48x48">
								          <img class="is-circular" :src="reply.user.avatar_url">
								        </p>
								      </figure>
									    <div class="media-content">
									        <div class="content">
									          <p>
									            <strong class="is-capitalized">{{reply.user.name}}</strong> <span v-if="reply.user.name === userName" class="tag is-primary">author</span>
									             &nbsp<span class="has-text-grey"> {{reply.created_at | postAgo}}</span>
									            <br>
									            <vue-markdown v-highlight :source="reply.content"></vue-markdown>
									            
									            <small>
									            	<a @click="toggleLike(reply.id,index,replyIndex)"><i v-if="reply.like" class="fa fa-thumbs-up" style="font-size:18px;"></i>
							        				<i v-else class="fa fa-thumbs-o-up" style="font-size:18px;"></i>
							        				{{reply.likes_count | formatLikes}}
							        				</a> · <a @click="makeReply(index, reply.user.name,$event)">Reply</a>
							        			</small>
									          </p>
									        </div>
									    </div>
									</article>


									
									<!-- replies info & make a reply button -->
							        <article class="media" key="repliesInfo">
									    <div class="media-content" >
									        <div class="content" v-if="comment.replies_count > show_replies && !comment.loadMore"> 	
									        	
												There are {{comment.replies_count - show_replies}} more replies, <a @click="loadMore(index)">click to view</a>
									        	
									        </div>
									    </div>
									    <div class="media-right">
									    	<div>
										  		<a class="button" @click="makeReply(index,null,$event)">Make a reply</a>
											</div>
										</div>
									</article>
									<!-- reply input textarea -->
										<article class="media" key="replyArea" v-if="selectedCommentIndex === index" >
										  <figure class="media-left is-hidden-mobile">
										    <p class="image is-64x64">
										      <img class="is-circular" :src="user.avatar_url">
										    </p>
										  </figure>
										  <div class="media-content">
										    <div class="field">
										      <p class="control">
										        <textarea class="textarea" v-model="reply_content" placeholder="Add a reply..."></textarea>
										      </p>
										    </div>
										    <div class="field">
										      <p class="control">
										        <button class="button" @click.prevent="postNewReply(comment.id, index)" :disabled="!reply_content">Post reply</button>
										      </p>
										    </div>
										  </div>
										</article>
								</template>
							</transition-group>

																



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
							      <img class="is-circular" :src="user.avatar_url">
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

.v-leave { opacity: 1; }
.v-leave-active { transition: opacity 0.3s }
.v-leave-to { opacity: 0; }
.v-enter { opacity: 0; }
.v-enter-active  { transition: opacity 0.5s }
.v-enter-to { opacity: 1; }

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
				pagination: {},
				selectedCommentIndex: -1,
				reply_content: '',
				show_replies: 3,
				taget: null
			};
		},
		computed: mapState({
    		isLogined: state => state.isLogined,
    		user: state => state.user
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
        	}
		},
		methods:{
			loadMore(index){
				if (!this.comments[index].hasOwnProperty('loadMore'))
					Vue.set(this.comments[index], 'loadMore', true);
			},
			postNewReply(comment_id, index){
				axios.post("/api/reply/store",{content: this.reply_content,
												 comment_id: comment_id
						})
						.then(response => {
							if (response.data.status === 'success'){
								//this.fetchComments(this.pagination.last_page_url);
								this.fetchComments(null, 
									() => {Vue.set(this.comments[index], 'toggle', true)}
									);
								
								// Vue.set(this.comments, this.comments.length, response.data.comment);
								this.reply_content = '';
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
			makeReply(index, to, $event){
				if (this.target == $event.target){
					if (this.selectedCommentIndex === index)
						this.selectedCommentIndex = -1;
					else
						this.selectedCommentIndex = index;
				}
				else{
					this.target = $event.target;
					this.selectedCommentIndex = index;
					this.reply_content = '';
				}
				if (to){
					this.reply_content = "@ "+ to.charAt(0).toUpperCase() + to.slice(1) + ": ";
				}
			},
			toggleReplies(index, comment_id){
				if (this.comments[index].hasOwnProperty('toggle'))
					Vue.set(this.comments[index], 'toggle', !this.comments[index].toggle);
				else
					Vue.set(this.comments[index], 'toggle', true);
			},
			toggleLike(id, index, replyIndex){
				if (this.isLogined){
					var url = '/api/comment/toggleLike';
					var param = {comment_id: id};
					if (replyIndex > -1){
						url = '/api/reply/toggleLike';
						param = {reply_id: id};
					}
					axios.post(url,param)
							.then(r => {
								if (replyIndex > -1){
									var reply = this.comments[index].replies[replyIndex];
									var state = reply.like;
									if (state){
										reply.likes_count--;
									}
									else{
										reply.likes_count++;
									}
									reply.like = !state;
									Vue.set(this.comments[index].replies, replyIndex, reply);
								}
								else{
									var state = this.likes[index];
									Vue.set(this.likes, index, !state);
									var comment = this.comments[index];
									if (state){
										comment.likes_count--;
									}
									else{
										comment.likes_count++;
									}
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
			fetchComments(pageUrl, then){
				this.isLoading = true;
				Post.fetchComments(data => {
					this.isLoading = false;
					this.comments = data.comments.data;
					this.likes = data.likes;
					this.setPagination(data.comments);
					if (then)
						then();
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

				console.log("?");
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
		}
	}
</script>


