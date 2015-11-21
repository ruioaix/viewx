@extends('index')


@section('title', 'Proxy Health')
@endsection


@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
@include('loadchartfunction')
<script> @include('proxy.hjs') </script>
@endsection


@section('content')
@include('fromtoform')
<h2 id='period'> </h2>
<h3> Hits </h3>
<a href='http://pachong.org'>Pachong.org</a> : <strong id='pachong_usage'></strong> <br>
<a href='http://proxylist.hidemyass.com/'>hidemyass subscription</a> : <strong id='hidemyass_usage'></strong><br>
<a href='freeproxylists.net'>freeproxylists.net</a> : <strong id='freeproxylists_usage'></strong><br>
<div id="canvas_container">
</div>
@endsection
