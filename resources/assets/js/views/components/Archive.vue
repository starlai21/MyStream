<template>
  <div class="control">
    <div class="tags has-addons" v-for="archive in archives" @click="tagClicked(archive.year,archive.month)">
      <a :class="[ isActive(archive.year,archive.month)  ? 'tag is-danger' :'tag is-link']">{{archive.year}}-{{archive.month}}</a>
      <a class="tag is-info">{{archive.published}}</a>
    </div>
  </div>
</template>
<script >
import Post from '../../models/Post.js';

	export default {
		props:['date'],
		data(){
			return{
				archives:[]
			}
		},
		created(){
			Post.archives(data => this.archives = data,{userName: this.$route.params.userName});
		},
		methods:{
			tagClicked(year,month){
				let date = {
					year:year,
					month:month
				};
				if (this.isActive(year,month))
					this.$emit('clearDate',{year:'',month:''});
				else
					this.$emit('dateUpdated',date);
			},
			isActive(year,month){
				if ($.isEmptyObject(this.date))
					return false;
				else if(this.date['year'] == year && this.date['month'] == month)
					return true;
				return false;
			},
			updateArchives(){
				Post.archives(data => this.archives = data,{userName: this.$route.params.userName});
			}
		},
		watch:{
			'$route.params.userName': 'updateArchives',
			'$store.state.notify': 'updateArchives'
		}
	}
</script>