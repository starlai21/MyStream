<template>
	<div class="tags">
		<template v-for="tag in tags">
			<a :class="[ tagName == tag.name ? 'tag is-danger' : 'tag is-link']" @click="tagClicked(tag.name)">{{tag.name}}</a>
		</template>
	</div>
</template>
<script>
	export default {
		props:['tags','tagName'],
		methods:{
			tagClicked(tag){
				if (tag == this.tagName)
					this.$emit('clearTag');
				else
					this.$emit('tagUpdated',tag);
			}
		}
	}
</script>