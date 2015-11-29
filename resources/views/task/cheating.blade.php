@extends('index')


@section('title', $title)
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
@include('loadchartfunction')
<script> @include('one_l_ft_js')</script>
@endsection

@section('content')
@include('fromtoform')
<h3> Found {{ count($cheatings) }} cheating</h3>
<ul>
@foreach ($cheatings as $cht) 
<li> <a href="{{ action('TaskController@czh', ["$cht"]) }}">{{ $cht }} </a></li>
@endforeach
</ul>
<div id="canvas_container"> </div>
@endsection
