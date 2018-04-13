@extends('layouts.master')


@section('body')
	<div id="app">
	    @include('layouts.header')    
	    <section class="section">
	        <div class="container">
		        	<keep-alive>
		        		{{-- <transition name="slide"> --}}
		            		<router-view v-if="$route.meta.keepAlive"></router-view>
		            	{{-- </transition> --}}
		        	</keep-alive>
		        	
		        		<router-view v-if="!$route.meta.keepAlive"></router-view>
	        		
	        </div>
	    </section>
	    @include('layouts.footer')  
	</div>
@stop