@extends('layouts.master')

@section('body')
	@foreach($posts as $post)
		<li>
			{{$post->title}}

		</li>
	@endforeach
<div class="container">
	<form>
		<div class="field">
		  <label class="label">Title</label>
		  <div class="control">
		    <input class="input" type="text" placeholder="Text input">
		  </div>
		</div>
		<input class="input" type="tags" placeholder="Add Tag" value="Tag1,Tag2,Tag3">


		<div class="field">
		  <label class="label">Abstract</label>
		  <div class="control">
		    <textarea class="textarea" placeholder="Textarea"></textarea>
		  </div>
		</div>


		<div class="field">
		  <label class="label">Content</label>
		  <div class="control">
		    <textarea class="textarea" placeholder="Textarea"></textarea>
		  </div>
		</div>


		<div class="field is-grouped">
		  <div class="control">
		    <button class="button is-link">Submit</button>
		  </div>
		  <div class="control">
		    <button class="button is-danger">Cancel</button>
		  </div>
		</div>
	</form>
</div>
@stop