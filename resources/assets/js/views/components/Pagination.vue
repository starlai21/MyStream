<template>
<nav class="pagination is-centered" role="navigation" aria-label="pagination">
    <a class="pagination-previous" @click="pagination.prev_page_url && go(pagination.prev_page_url)" :disabled="!pagination.prev_page_url">Previous</a>
    <a class="pagination-next" @click="pagination.next_page_url && go(pagination.next_page_url)" :disabled="!pagination.next_page_url">Next page</a>
    <ul class="pagination-list">
      <li v-for="r in range">
      	<span v-if="r== '...'" class="pagination-ellipsis">&hellip;</span>
      	<a v-else :class="[ r == pagination.current_page ? 'pagination-link is-current' :'pagination-link']" :aria-label="'Goto page '+r" @click=" r != pagination.current_page && go(pagination.path+'?page='+r)">
      		{{r}}
      	</a>
      </li>
    </ul>
</nav>
</template>

<script type="text/javascript">
	export default{
		props:['pagination'],
		methods:{
			go(url){
				this.$emit('go',url);
			},
			getPagination(c,m){
				var current = c,
			    last = m,
			    delta = 2,
			    left = current - delta,
			    right = current + delta + 1,
			    range = [],
			    rangeWithDots = [],
			    l;

			    for (let i = 1; i <= last; i++) {
			        if (i == 1 || i == last || i >= left && i < right) {
			            range.push(i);
			        }
			    }

			    for (let i of range) {
			        if (l) {
			            if (i - l === 2) {
			                rangeWithDots.push(l + 1);
			            } else if (i - l !== 1) {
			                rangeWithDots.push('...');
			            }
			        }
			        rangeWithDots.push(i);
			        l = i;
			    }

			    return rangeWithDots;
			}
		},
		watch:{
			pagination(n,o){
				this.range = this.getPagination(n.current_page,n.last_page);
			}
		},
		data(){
			return{
				range:[]
			};
		}

	}
</script>