<template>
	<div>
<!-- 			<span class="icon"  @click="$router.go(-1)">
				<i class="fa fa-angle-double-left fa-3x"></i>
			</span> -->

		<div class="columns">
  			<div class="column is-three-fifths is-offset-one-fifth">
  				<div class="field">
				  <label class="label">Title</label>
				  <div class="control">
				    <input class="input" type="text" v-model="title" @input="clearError('title')">
				  </div>
				  <p class="help is-danger" v-if="errors_.title">{{errors_.title[0]}}</p>
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
				    <textarea class="textarea" v-model="content" @input="clearError('content')"></textarea>
					
				  </div>
				  <p class="help is-danger" v-if="errors_.content">{{errors_.content[0]}}</p>
				</div>
				<div class="field">
					<a class="button is-primary" @click="Post">Post</a>
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
				errors_:[]
			};
		},
		created(){

		},
		components:{
			InputTag
		},
		methods:{
			Post(){
				let params = {
					title: this.title,
					abstract: this.abstract,
					tags: this.tags,
					content: this.content
				}
				axios.post('/api/post/create',params)
						.then(r => {
							//console.log(r);
							if (r.data.status == "error"){
								Post();
							}
							else{
								this.$emit("posted",r.data.post);
								this.clear();
								Swal({
									  type:'success',
				                      title: 'Post updated!'
				                });
							}
						})
						.catch(e => {
							console.log(e);
							Swal({
								  type:'error',
			                      title: 'Failed to update.'
			                });
			                this.errors_ = e.response.data.errors; 
						});

			},
			clear(){
				this.title = '';
				this.abstract = '';
				this.tags = [];
				this.content = '';
				this.errors_ = [];
			},
			clearError(prop){
				if(this.errors_.hasOwnProperty(prop))
					this.errors_[prop] = null;
			}
		}

	}
</script>