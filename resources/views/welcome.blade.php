@extends('layouts.master')


@section('body')
	<div id="app">
	    @include('layouts.header')    
	    <section class="section">
	        <div class="container body">
	        	<transition name="fade">
		        	<keep-alive>
		            	<router-view v-if="$route.meta.keepAlive"></router-view>
		        	</keep-alive>
		        </transition>
		        <transition name="fade">
		        	<router-view v-if="!$route.meta.keepAlive"></router-view>
		        </transition>
	        		
	        </div>
	    </section>
	    @include('layouts.footer')  
	</div>
@stop