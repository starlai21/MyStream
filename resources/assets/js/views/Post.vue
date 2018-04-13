<template>
<div>
	<fingerprint-spinner v-show="isLoading"
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
	<div class="columns" v-show="!isLoading">
	  	<div class="column is-8">
			<div>
				<div class="content">
				  	<h1 class="has-text-centered">{{post.title}}</h1>

				  	<nav class="level is-mobile">
					  <div class="level-item level-left has-text-centered">
					  	<tag :tags="post.tags"></tag>
					  </div>
					 
					  <div class="level-item level-right has-text-centered">
					  	<span class="tag is-light">{{post.created_at | postOn}}</span>
					  </div>
					</nav>
					<vue-markdown :source="post.content"  :toc="true" toc-class="menu-list" toc-id="table" @toc-rendered="processAnchors" :toc-first-level="1" :toc-last-level="3"
					 v-highlight></vue-markdown>
				  	<!-- <div v-html="markDown(post.content)" v-highlight></div> -->
				</div>
			</div>
		</div>
		<div class="column is-3 is-offset-1 is-hidden-mobile" v-show="!isLoading">
			<div id="toc" class="follow-scroll">
				<p class="menu-label">
			    	Table of Contents
			  	</p>
				<div id="table"></div>				
			</div>

		</div>
	</div>
</div>

</template>
<style>
	.follow-scroll {
    position: relative;
}
</style>

<script>
import Post from '../models/Post.js';
import Tag from './components/Tag';
import moment from 'moment';
import VueMarkdown from 'vue-markdown'


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
					
					var originalLastY = $("#table li a[href^='#']").last().offset().top,
						originalFirstY = $(".menu-label").first().offset().top,
						originalScrollDistance = originalLastY - originalFirstY,
						scrollDistance = 0;

					var topMargin = 200;

					var segs = [];
					$("a.toc-anchor").each(function(index,node){
						segs.push(node.parentNode);
						});

					$(window).bind('scroll',function(){

						var scrollTop = $(this).scrollTop();

						//scroll toc
						if (scrollTop >= $("#table li a[href^='#']").last().offset().top - topMargin){
							scrollDistance += originalScrollDistance;
							var x = scrollTop
							$('#toc').stop(false,false).animate({top: x},50);
							// alert($("#table li a[href^='#']").last().offset().top);
						}

						// if (scrollTop + originalFirstY + topMargin <= $(".menu-label").first().offset().top){
						// 	if (scrollDistance != 0){
						// 		scrollDistance -= originalScrollDistance/2;
						// 	}
						// 	$('#toc').stop(false,false).animate({top: scrollDistance},50);
						// }


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
			this.isLoading = true;
			Post.fetchPost(data => {
				this.post = data;
				this.isLoading = false;
			},error => {
              this.isLoading = false;
              console.log(error);
              this.$router.push({name:'home'});
            },this.postId);

		},
		components:{
			'tag':Tag,
			VueMarkdown
		}
	}
</script>


