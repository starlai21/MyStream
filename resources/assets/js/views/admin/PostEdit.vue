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
				<mavon-editor codeStyle="solarized-light" v-model="content" @input="clearError('content')" ref=md @imgAdd="addImage" @imgDel="deleteImage"></mavon-editor>
			</div>
			<p class="help is-danger" v-if="errors_.content">{{errors_.content[0]}}</p>
		</div>
	</section>
	<footer class="modal-card-foot">
	  <button class="button is-success" @click="update">Update</button>
	</footer>
</div>
</template>
<style type="text/css">
	.dropdown-item button{
		top:4px;
	}
</style>
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
				errors_: [],
				img_file: {}
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
			addImage(pos, $file){
	           	this.img_file[pos] = $file;
	        },
	        deleteImage(pos){
	        	delete this.img_file[pos];
	        },
	        uploadImages(then){
	        	if ($.isEmptyObject(this.img_file)){
	        		then();
	        	}
	        	else{
	        		var formdata = new FormData();
		            for(var _img in this.img_file){
		                formdata.append(_img, this.img_file[_img]);
		            }
		            axios({
		                url: '/api/image/store',
		                method: 'post',
		                data: formdata,
		                headers: { 'Content-Type': 'multipart/form-data' },
		            }).then(({data}) => {
		                for (var key in data) {
		                    this.$refs.md.$img2Url(key,data[key]);
		                }
		                Vue.nextTick().then( () => {
		                	then();
		                });
		                
		            }).catch(e => {
		            	console.log(e);
		            	then();
		            });	        		
	        	}

	        },
	        update(){
	        	this.uploadImages(this.send);
	        },
			send(){
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