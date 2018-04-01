@extends('layouts.master')


@section('body')
	<div id="app">
	    @include('layouts.header')    
	    <section class="section body">
	        <div class="container">
	        	<keep-alive>
	            	<router-view v-if="$route.meta.keepAlive"></router-view>
	        	</keep-alive>
	        	<router-view v-if="!$route.meta.keepAlive"></router-view>
	        </div>
	    </section>
	    @include('layouts.footer')  
	</div>
@stop