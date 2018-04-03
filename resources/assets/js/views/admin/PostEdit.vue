<template>
	<div>
		<fingerprint-spinner v-if="isLoading"
		  :animation-duration="1500"
		  :size="64"
		  color="#00d1b2"
		  style="position: fixed;top: 50%;left: 50%;
  transform: translate(-50%, -50%);"
		/>
		<div class="columns" v-if="!isLoading">
  			<div class="column is-three-fifths is-offset-one-fifth">
  				<div class="field">
				  <label class="label">Title</label>
				  <div class="control">
				    <input class="input" type="text" v-model="post.title">
				  </div>
				</div>

				<div class="field">
				  <label class="label">Tags</label>
				  <div class="control">
				    <input class="input" type="tags" placeholder="Add Tag" value="Tag1,Tag2,Tag3">
				  </div>
				</div>
				

				<div class="field">
				  <label class="label">Abstract</label>
				  <div class="control">
				    <textarea class="textarea" v-model="post.abstract"></textarea>
				  </div>
				</div>
				<div class="field">
				  <label class="label">Content</label>
				  <div class="control">
				    <textarea class="textarea" v-model="post.content"></textarea>
				  </div>
				</div>
				<div class="field">
					<button class="button is-primary">Update</button>
				</div>
  			</div>
		</div>
	</div>
</template>

<script>

import bulmaCalendar from 'bulma-tagsinput/dist/bulma-tagsinput.min.js';
import Post from '../../models/Post.js';

	export default{
		data(){
			return {
				post:[],
				isLoading: false,
				postId:this.$route.params.postId
			};
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
		}

	}
</script>