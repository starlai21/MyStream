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
			<back-to-top text="Back to top" visibleOffset="600">
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
							  <div class="level-item  has-text-centered">
							  	<tag :tags="post.tags"></tag>
							  </div>
							 
							  <div class="level-item  has-text-centered">
							  	<span class="tag is-light">{{post.created_at | postOn}}</span>
							  	<span class="tag is-primary">{{post.user && post.user.name}}</span>
							  </div>
		
							</nav>
							
							<vue-markdown v-highlight :source="post.content"  :toc="true" toc-class="menu-list" toc-id="table" @toc-rendered="processAnchors" :toc-first-level="1" :toc-last-level="3"
							 ></vue-markdown>
							
						  	<!-- <div v-html="markDown(post.content)" v-highlight></div> -->
						</div>
						<div id="gitalk-container"></div>
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
</div>

</template>
<style>

</style>

<script>
import Post from '../../models/Post.js';
import Tag from '../components/Tag';
import moment from 'moment';
import VueMarkdown from 'vue-markdown';
import BackToTop from 'vue-backtotop';
import 'gitalk/dist/gitalk.css';
import Gitalk from 'gitalk';
import Header from './Header';


	export default {
		props:['postId','userName'],
		data(){
			return {
				isLoading: false,
				post:[]
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
			console.log(this.postId+" " + this.userName);
			this.isLoading = true;
			Post.fetchPost(data => {
				this.post = data;
				this.isLoading = false;
			},error => {
              this.isLoading = false;
              console.log(error);
              this.$router.push({name:'blog_home', params: {userName: this.userName}});
            },{postId: this.postId, userName: this.userName});

		},
		components:{
			'tag':Tag,
			VueMarkdown,
			BackToTop,
			'blog-header': Header
		},
		mounted(){
			// const gitalk = new Gitalk({
			//   clientID: '47cbca2404878e6637ab',
			//   clientSecret: '90ad2bdb2db2b14ceeac4be7035e2cbd8f949b1a',
			//   repo: 'MyStream',
			//   owner: 'starlai21',
			//   admin: ['starlai21'],
			//   id: location.pathname,      // Ensure uniqueness and length less than 50
			//   distractionFreeMode: false  // Facebook-like distraction free mode
			// })

			// gitalk.render('gitalk-container')
		}
	}
</script>


