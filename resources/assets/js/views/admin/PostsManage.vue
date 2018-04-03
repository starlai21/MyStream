<template>

	<div >
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
					
					<table class="table">
					  <thead>

					    <tr>
					      <th>Title</th>
					      <th>Post Date</th>
					      <th>Operations</th>
					    </tr>
					  </thead>
					  <tbody>
					  	<tr v-for="post in posts">
					  		<th>{{post.title}}</th>
					  		<th>{{post.created_at|postOn}}</th>
					  		<th>
					  			<a class="button is-primary">
					  				<router-link :to="{name:'post_edit',params:{postId:post.id}}">Edit</router-link>
					  			</a>
								<a class="button is-danger">Delete</a>
							</th>
					  	</tr>
					  </tbody>
					</table>
					
				</div>

				<div class="column auto">
					<div class="tile is-parent is-vertical">
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
		<pagination @go="fetchPosts" :pagination="pagination" v-if="!isLoading"></pagination>

	</div>
</template>

<script>

import PostsMixin from '../../mixins/PostsMixin.js';
import moment from 'moment';
	export default {
		mixins: [PostsMixin],
		filters:{
			postOn(created_at){
                return moment(created_at).format("MM-DD,YYYY");
            }
		}
	}
</script>