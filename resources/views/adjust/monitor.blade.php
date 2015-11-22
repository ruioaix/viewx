@extends('index')


@section('title', 'XUEQIU Adjust')
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
@include('loadchartfunction')
<script> @include('adjust.mjs') </script>
@endsection

@section('content')
@include('fromtoform')
<div id="canvas_container"> </div>
@endsection
