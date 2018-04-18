<template>
	<article class="tile is-child box">
            
    	<p class="title">
    	  	<router-link :to="{ name: 'post',params:{postId: post.id, userName: userName} }">
    	    	{{post.title}} <span class="tag is-light">{{postOn(post)}}</span>
    	  	</router-link>
    	</p>          
    	<div class="control">
    		<tags :tags="post.tags" :tagName="tagName" @tagUpdated="tagUpdateHandler"></tags>
    	</div>
    	<p class="subtitle" v-html="markDown(post.abstract)" v-highlight></p>
	</article>
</template>
<script>
import moment from 'moment';
import Tag from './Tag';
	export default {

	props:['post','tagName','userName'],

	methods:{
		postOn(post){
                return moment(post.created_at).fromNow();
            },
        tagUpdateHandler(tag){
            this.$emit('tagUpdated',tag);
        },
        markDown(content){
            if (content)
                return marked(content);
        }
	},
	components:{
		'tags':Tag
	}
}	
</script>
