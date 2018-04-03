import Post from '../models/Post.js';
import Archive from '../views/components/Archive';
import Tag from '../views/components/Tag';
import Pagination from '../views/components/Pagination';

export default {

	components:{
		'archive': Archive,
 	    'tags': Tag,
 	    'pagination': Pagination
	},
	data(){
  	    return{
  	        isLoading:false,
  	        posts:[],
  	        tags:[],
  	        pagination:{},
  	        params:{},
  	        activeTag:"",
  	        activeDate:{}
  	    };
  	},
  	created(){
  	    this.isLoading = true;

	    Post.all(data => {
  	      this.posts = data.data;
  	      this.setPagination(data);
  	      this.isLoading = false;
  	    },error => {
  	      this.isLoading = false;
  	      console.log(error);
  	    });
  	    Post.tags(data => this.tags = data);
  	},
  	methods:{
  	  setPagination(data){
  	    let pagination = {
  	            current_page: data.current_page,
  	            last_page: data.last_page,
  	            next_page_url: data.next_page_url,
  	            prev_page_url: data.prev_page_url,
  	            path:data.path
  	        };
  	    this.pagination = pagination;
  	  },
  	  fetchPosts(pageUrl){
  	    this.isLoading = true;
  	    Post.all(data => {
  	      this.posts = data.data;
  	      this.setPagination(data);
  	      this.isLoading = false;
  	    },error => {
  	      this.isLoading = false;
  	      console.log(error);
  	    },
  	    pageUrl,this.params);
  	    this.params = {};
  	  },
  	  setDate(date){
  	    this.params['month'] = date.month;
  	    this.params['year'] = date.year;
  	    this.params['tag'] = this.activeTag;
  	    this.activeDate = date;
  	    this.fetchPosts();
  	  },
  	  setTag(tag){
  	    this.params['tag'] = tag;
  	    this.params['month'] = this.activeDate['month'];
  	    this.params['year'] = this.activeDate['year'];
  	    this.activeTag = tag;
  	    this.fetchPosts();
  	  }
  	}
}