<template>

	<div >
		<!-- modal create post begin -->
		<div class="modal" :class="{'is-active':isCreateActive}">
		  <div class="modal-background"></div>
		  <post-create @posted="postCreated"></post-create>
		  <button class="modal-close is-large" aria-label="close"  @click="createModalClose"></button>
		</div>
		<!-- modal end -->

		<!-- modal edit post begin -->
		<div class="modal" :class="{'is-active':isEditActive}">
		  <div class="modal-background"></div>
		  <post-edit :post="postEdited" @updated="postUpdated"></post-edit>
		  <button class="modal-close is-large" aria-label="close"  @click="editModalClose"></button>
		</div>
		<!-- modal end -->


		<fingerprint-spinner v-if="isLoading"
		  :animation-duration="1500"
		  :size="64"
		  color="#00d1b2"
		  style="position: fixed;top: 50%;left: 50%;
  transform: translate(-50%, -50%);"
		/>
		<div v-else>
			<div class="columns">
				<div class="column is-four-fifths ">
					<div class="is-responsive">
					<table class="table is-bordered is-narrow is-hoverable is-fullwidth">
					  <thead>

					    <tr>
					      <th>Title</th>
					      <th>Post Date</th>
					      <th>Actions</th>
					    </tr>
					  </thead>
					  <tbody>
					  	<tr v-for="post in posts">
					  		<th>
					  			<router-link :class="[post.posted == 1 ? 'has-text-success': 'has-text-danger']" :to="{ name: 'post',params:{postId: post.id, userName: userName} }">
					  				{{post.title}} 
					  			</router-link>
					  		</th>
					  		<th>{{post.created_at|postOn}}</th>
					  		<th>
					  			<button v-tooltip.top="'edit'" class="button is-primary" @click="activateEdit(post)">
					  				<i class="fa fa-edit" style="font-size:20px"></i>
					  			</button>
								<button v-tooltip.top="'switch to public mode'" class="button is-warning" v-if="post.posted === 0" @click="togglePostStatus(post.id,post.posted)">
									<i class="fa fa-eye" style="font-size:20px" ></i>
								</button>
								<button v-tooltip.top="'switch to draft mode'" class="button is-warning" v-else @click="togglePostStatus(post.id,post.posted)">
									<i class="fa fa-eye-slash" style="font-size:20px"></i>
								</button>
								<button v-tooltip.top="'delete'" class="button is-danger" @click="deletePost(post.id)">
									<i class="fa fa-trash" style="font-size:24px"></i>
								</button>
							</th>
					  	</tr>
					  </tbody>
					</table>
				</div>
					
				</div>

				<div class="column auto">
					<div class="tile is-parent is-vertical">
						<div class="tile is-child">
							<a class="button is-success"  @click="isCreateActive = !isCreateActive">Create A New Post</a>
						</div>
						<div class="tile is-child box">
							<span class="icon" @click="setDate({year:'',month:''})">
				              <i class="fa fa-calendar" style="font-size:20px;color:blue"></i>
				            </span>
				          	<archive @dateUpdated="setDate" @clearDate="setDate" :date="activeDate">
							</archive>
						</div>
					

						<div class="tile is-child box">
							<span class="icon" @click="setTag('')">
			             		 <i class="fa fa-tags" style="font-size:20px;color:blue"></i>
			          		</span>
			         		 <tags @tagUpdated="setTag" @clearTag="setTag" :tags="tags" :tagName="activeTag"></tags>
			         	</div>
		         	</div>
				</div>
			</div>
			 
		</div>
		<pagination @go="updatePosts" :pagination="pagination" v-show="!isLoading"></pagination>

	</div>
</template>
<style>
	.is-responsive{
		overflow-x: auto;
	},
	.my-popper {
    	background: #FFC107;
    	color: black;
    	width: 150px;
    	border-radius: 3px;
    	box-shadow: 0 0 2px rgba(0,0,0,0.5);
    	padding: 10px;
    	text-align: center;
    }
</style>
<script>

import PostsMixin from '../../mixins/PostsMixin.js';
import PostCreate from './PostCreate';
import PostEdit from './PostEdit';
import moment from 'moment';
import {mapState} from 'vuex';
	export default {
		data(){
			return {
				isCreateActive: false,
				isEditActive: false,
				postEdited:null
			};
		},
		mounted(){

		},
		components:{
			PostCreate,
			PostEdit
		},
    	computed: mapState({
    		userName: state => state.user.name,
    		blog: state => state.blog
    	}),
		mixins: [PostsMixin],
		filters:{
			postOn(created_at){
                return moment(created_at).format("MM-DD,YYYY");
            }
		},
		methods:{
			togglePostStatus(postId,status){
			  	axios.post(`/api/post/${postId}/toggle`)
						.then(r => {
							Swal({
								  type:'success',
			                      title: status === 1 ? 'The post is in draft state now.':'The post is published!'
			                });
			          //       const index = this.posts.findIndex(item => item.id === postId);
			        		// this.$set(this.posts[index], 'posted', !status);
			                
							this.$store.commit('notify');
						})
						.catch(e => {
							console.log(e);
							Swal({
								  type:'error',
			                      title: 'Failed to change the status.'
			                });
						});
			},
			deletePost(postId){
				Swal({
					  title: 'Are you sure?',
					  text: "You won't be able to revert this!",
					  type: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes, delete it!'
					}).then((result) => {
					  if (result.value) {
					  	axios.post(`/api/post/${postId}/delete`)
								.then(r => {
									Swal({
										  type:'success',
					                      title: 'Post deleted!'
					                });
					          //       this.posts = this.posts
									  				// .filter(e => e.id != postId);
									this.$store.commit('notify');
								})
								.catch(e => {
									console.log(e);
									Swal({
										  type:'error',
					                      title: 'Failed to delete the post.'
					                });
								});
					  }
					});
			},
			activateEdit(post){
				this.postEdited = post;
				this.isEditActive = !this.isEditActive;
			},
			postCreated(post){
				this.isCreateActive = !this.isCreateActive;
				this.$store.commit('notify');
			},
			postUpdated(){
				this.isEditActive = !this.isEditActive;
				this.$store.commit('notify');
			},
			createModalClose(){
				Swal({
					  title: 'Are you sure to leave?',
					  text: "You have not save the post yet!",
					  type: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes, I wanna leave!'
					}).then((result) => {
					  if (result.value) {
					  	this.isCreateActive = !this.isCreateActive;
					  }
				});
				
			},
			editModalClose(){
				Swal({
					  title: 'Are you sure to leave?',
					  text: "You have not save the change yet!",
					  type: 'warning',
					  showCancelButton: true,
					  confirmButtonColor: '#3085d6',
					  cancelButtonColor: '#d33',
					  confirmButtonText: 'Yes, I wanna leave!'
					}).then((result) => {
					  if (result.value) {
					  	this.isEditActive = !this.isEditActive;
					  }
				});
			}
		}
	}
</script>