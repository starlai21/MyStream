<template>
	<div>

		<div class="columns">
  			<div class="column is-three-fifths is-offset-one-fifth">
  				<div class="field">
				  <label class="label">Title</label>
				  <div class="control">
				    <input class="input" type="text" v-model="title">
				  </div>
				</div>

				<div class="field">
				  <label class="label">Tags</label>
				  <div class="control">
				    <input-tag :tags.sync="tags"></input-tag>
				  </div>
				</div>
				

				<div class="field">
				  <label class="label">Abstract</label>
				  <div class="control">
				    <textarea class="textarea" v-model="abstract"></textarea>
				  </div>
				</div>
				<div class="field">
				  <label class="label">Content</label>
				  <div class="control">
				    <textarea class="textarea" v-model="content"></textarea>
				  </div>
				</div>
				<div class="field">
					<a class="button is-primary" @click="Update">Update</a>
				</div>
  			</div>
		</div>
	</div>
</template>

<script>
import InputTag from 'vue-input-tag';
import Post from '../../models/Post.js';

	export default{
		props:['post'],
		data(){
			return {
				title: '',
				abstract: '',
				tags: [],
				content: '',
				postId: null
			};
		},
		watch:{
			post(n,o){
				this.title = n.title;
				this.abstract = n.abstract;
				this.tags = n.tags.map(i => i.name);
				this.content = n.content;
				this.postId = n.id;
			}
		},
		created(){

		},
		components:{
			InputTag
		},
		methods:{
			Update(){
				let params = {
					title: this.title,
					abstract: this.abstract,
					tags: this.tags,
					content: this.content
				}
				axios.post(`/api/post/${this.postId}/edit`,params)
						.then(r => {
							//console.log(r);
							this.$emit('updated');
							Swal({
								  type:'success',
			                      title: 'Post updated!'
			                });
						})
						.catch(e => {
							console.log(e);
							Swal({
								  type:'error',
			                      title: 'Failed to update.'
			                });
						});

			}
		}

	}
</script>