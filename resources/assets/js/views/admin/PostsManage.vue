<template>

	<div >
		<!-- modal create post begin -->
		<div class="modal" :class="{'is-active':isCreateActive}">
		  <div class="modal-background"></div>
		  <div class="modal-card">
		    <div class="box">
		    	<post-create @posted="postCreated"></post-create>
			</div>
		  </div>
		  <button class="modal-close is-large" aria-label="close"  @click="isCreateActive = !isCreateActive"></button>
		</div>
		<!-- modal end -->

		<!-- modal create post begin -->
		<div class="modal" :class="{'is-active':isEditActive}">
		  <div class="modal-background"></div>
		  <div class="modal-card">
		    <div class="box">
		    	<post-edit :post="postEdited" @updated="postUpdated"></post-edit>
			</div>
		  </div>
		  <button class="modal-close is-large" aria-label="close"  @click="isEditActive = !isEditActive"></button>
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
				<div class="column is-four-fifths">
					
					<table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
					  <thead>

					    <tr>
					      <th>Title</th>
					      <th>Post Date</th>
					      <th>Actions</th>
					    </tr>
					  </thead>
					  <tbody>
					  	<tr v-for="post in posts">
					  		<th>{{post.title}}</th>
					  		<th>{{post.created_at|postOn}}</th>
					  		<th>
					  			<button class="button is-primary" @click="activateEdit(post)">
					  				<i class="fa fa-edit" style="font-size:20px"></i>
					  			</button>

								<button class="button is-danger" @click="deletePost(post.id)">
									<i class="fa fa-trash" style="font-size:24px"></i>
								</button>
							</th>
					  	</tr>
					  </tbody>
					</table>
					
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
		components:{
			PostCreate,
			PostEdit
		},
    	computed: mapState({
    		userName: state => state.userName
    	}),
		mixins: [PostsMixin],
		filters:{
			postOn(created_at){
                return moment(created_at).format("MM-DD,YYYY");
            }
		},
		methods:{
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
					                this.posts = this.posts
									  				.filter(e => e.id != postId);
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
				// this.posts.unshift(post);
				this.$store.commit('notify');
			},
			postUpdated(){
				this.isEditActive = !this.isEditActive;
				this.$store.commit('notify');
			}
		}
	}
</script>