<template>
	<div>
		<div class="columns">
  			<div class="column is-three-fifths is-offset-one-fifth">
  				<div class="field">
				  <label class="label">Blog Name</label>
				  <div class="control">
				    <input class="input" type="text" v-model="blog.name" @input="clearError('name')">
				  </div>
				  <p class="help is-danger" v-if="errors.name">{{errors.name[0]}}</p>
				</div>

				<div class="field">
				  <label class="label">Blog Theme</label>
				  <div class="control">
					<div class="select">
    					<select v-model="blog.color">
      						<option>is-primary</option>
      						<option>is-success</option>
      						<option>is-danger</option>
      						<option>is-info</option>
      						<option>is-link</option>
      						<option>is-warning</option>
      						<option>is-light</option>
      						<option>is-dark</option>
    					</select>
  					</div>
  					<span class="bd-color" :style="{'background': colorHash[blog.color]}"></span>
  					
				  </div>

				</div>
				<div class="field">
				  <label class="label">Post Pagination</label>
				  <div class="control">
					<div class="select">
						<select v-model="blog.pagination">
							<option>4</option>
							<option>6</option>
							<option>8</option>
							<option>10</option>
						</select>
					</div>
				  </div>
				</div>
				

				<div class="field">
				  <label class="label">Blog Slogan</label>
				  <div class="control">
				    <textarea class="textarea" v-model="blog.slogan"></textarea>
				  </div>
				  
				</div>
				<div class="field">
					<a class="button is-primary" @click="save">Save</a>
				</div>
  			</div>
		</div>
	</div>
</template>

<style type="text/css">
.bd-color {
    border-radius: 2px;
    box-shadow: 0 2px 3px 0 rgba(0,0,0,.1), inset 0 0 0 1px rgba(0,0,0,.1);
    display: inline-block;
    float: left;
    height: 34px;
    margin-right: 8px;
    width: 34px;
}
</style>


<script>
import {mapState} from 'vuex';
export default {
	data(){
		return {
			colorHash: {
				'is-dark': 'hsl(0, 0%, 21%)',
				'is-light': 'hsl(0, 0%, 96%)',
				'is-primary': 'hsl(171, 100%, 41%)',
				'is-info': 'hsl(204, 86%, 53%)',
				'is-link': 'hsl(217, 71%, 53%)',
				'is-success': 'hsl(141, 71%, 48%)',
				'is-warning': 'hsl(48, 100%, 67%)',
				'is-danger': 'hsl(348, 100%, 61%)'
			},
			errors: []
		};
	},
	computed: mapState({
    	blog: state => state.blog
    }),
    methods:{
    	clearError(field){
    		this.errors[field] = '';
    	},
    	save(){
    		axios.post('/api/blog/update', {name: this.blog.name, 
    										color: this.blog.color, 
    										slogan: this.blog.slogan, 
    										pagination: this.blog.pagination})
    				.then(r => {
    					if (r.data.status == 'error'){
    						save();
    					}
    					else{
							Swal({
								type:'success',
				            	title: 'Blog setting has been saved.'
				        	});
    					}

    				})
    				.catch(e => {
    					Swal({
							type:'error',
				            title: 'Something went wrong.'
				        });
    					this.errors = e.response.data.errors;
    				});
    	}
    }
}
</script>