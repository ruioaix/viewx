@extends('index')


@section('title', 'XUEQIU Account Health')
@endsection


@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
@include('loadchartfunction')
<script> @include('account.hjs') </script>
@endsection


@section('content')
@include('fromtoform')
<h2 id='period'> </h2>
<h3> Hits </h3>
<strong id='usage'></strong>
<div id="canvas_container">
</div>
@endsection
