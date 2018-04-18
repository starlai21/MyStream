@extends('layouts.master')


@section('body')
	<div id="app">  
	    <transition name="fade">
		    <keep-alive>
		        <router-view v-if="$route.meta.keepAlive"></router-view>
		    </keep-alive>
		</transition>
		
		<transition name="fade">
		    <router-view v-if="!$route.meta.keepAlive"></router-view>
		</transition>
	        		
	    {{-- @include('layouts.footer')   --}}
	</div>
@stop