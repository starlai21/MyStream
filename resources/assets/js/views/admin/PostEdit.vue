<template>
<div class="modal-card">
	<header class="modal-card-head">
		<p class="modal-card-title">Update Post</p>
	</header>
	<section class="modal-card-body">
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
				<mavon-editor codeStyle="solarized-light" v-model="content" @input="clearError('content')"></mavon-editor>
			</div>
			<p class="help is-danger" v-if="errors_.content">{{errors_.content[0]}}</p>
		</div>
	</section>
	<footer class="modal-card-foot">
	  <button class="button is-success" @click="update">Update</button>
	</footer>
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
				postId: null,
				errors_: []
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
			update(){
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
			                this.errors_ = e.response.data.errors; 
						});

			},
			clearError(prop){
				if(this.errors_.hasOwnProperty(prop))
					this.errors_[prop] = null;
			}

		}

	}
</script>