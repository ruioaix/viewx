@extends('index')


@section('title', $title)
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
@include('loadchartfunction')
<script> @include('one_canvas_line_from_to_js')</script>
@endsection

@section('content')
@include('fromtoform')
<div id="canvas_container"> </div>
@endsection
