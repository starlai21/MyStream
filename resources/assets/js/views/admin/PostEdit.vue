<template>
	<div>
			<span class="icon"  @click="$router.go(-1)">
				<i class="fa fa-angle-double-left fa-3x"></i>
			</span>
		<fingerprint-spinner v-if="isLoading"
		  :animation-duration="1500"
		  :size="64"
		  color="#00d1b2"
		  style="position: fixed;top: 50%;left: 50%;
  transform: translate(-50%, -50%);"
		/>
		<div class="columns" v-else>
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
					<button class="button is-primary" @click="Update">Update</button>
				</div>
  			</div>
		</div>
	</div>
</template>

<script>
import InputTag from 'vue-input-tag';
import Post from '../../models/Post.js';

	export default{
		data(){
			return {
				title: '',
				abstract: '',
				tags: [],
				content: '',
				isLoading: false,
				postId:this.$route.params.postId
			};
		},
		created(){
			this.isLoading = true;
			Post.fetchPost(data => {
				this.isLoading = false;
				this.title = data.title;
				this.abstract = data.abstract;
				this.tags = data.tags.map( o => o.name);
				this.content = data.content;
			},error => {
              this.isLoading = false;
              console.log(error);
            },this.postId);
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