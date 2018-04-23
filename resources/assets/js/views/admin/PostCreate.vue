<template>
<div class="modal-card">
	<header class="modal-card-head">
		<p class="modal-card-title">New Post</p>
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
	  <button class="button is-success" @click="post">Post</button>
	  <button class="button is-primary" @click="save">Save</button>
	</footer>
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
				errors_: [],
				posted: false
			};
		},
		created(){

		},
		components:{
			InputTag
		},
		methods:{
			post(){
				this.posted = true;
				this.send();
			},
			save(){
				this.posted = false;
				this.send();
			},
			send(){
				let params = {
					title: this.title,
					abstract: this.abstract,
					tags: this.tags,
					content: this.content,
					posted: this.posted
				}
				axios.post('/api/post/create',params)
						.then(r => {
							//console.log(r);
							if (r.data.status == "error"){
								post();
							}
							else{
								this.$emit("posted",r.data.post);
								this.clear();
								Swal({
									  type:'success',
				                      title: this.posted ? 'It has been posted!' : 'It has been saved!'
				                });
							}
						})
						.catch(e => {
							console.log(e);
							Swal({
								  type:'error',
			                      title: 'Something went wrong.'
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