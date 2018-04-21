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
        <vue-markdown v-highlight :source="post.abstract" v-if="post.abstract"></vue-markdown>
	</article>
</template>
<script>
import moment from 'moment';
import Tag from './Tag';
import VueMarkdown from 'vue-markdown';
	export default {

	props:['post','tagName','userName'],

	methods:{
		postOn(post){
                return moment(post.created_at).fromNow();
            },
        tagUpdateHandler(tag){
            this.$emit('tagUpdated',tag);
        }
	},
	components:{
		'tags':Tag,
        VueMarkdown
	}
}	
</script>
