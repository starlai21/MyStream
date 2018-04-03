export default class Post{
	static all(then,_catch,pageUrl,params){
		pageUrl = pageUrl || '/api/posts';
		axios.get(pageUrl,{params:params})
                 .then(({data}) => then(data))
                 .catch(error => {
                 	_catch(error);
                 	Swal({
		                type: 'error',
		                title: 'Oops...',
		                text: 'Failed to fetch posts.'
		              });
                 });
	}

	static archives(then){
		axios.get('/api/posts/archives')
				.then(({data}) => then(data))
				.catch(error => console.log(error));
	}

	static tags(then){
		axios.get('/api/posts/tags')
				.then(({data}) => then(data))
				.catch(error => console.log(error));
	}

	static fetchPost(then,_catch,postId){
		axios.get('/api/posts/'+postId)
				.then(({data}) => then(data))
				.catch(error => {
					_catch(error);
					Swal({
		                type: 'error',
		                title: 'Oops...',
		                text: 'Failed to fetch the post.'
		              });
				});
	}
}