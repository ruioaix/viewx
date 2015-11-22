@extends('index')

@section('title', 'Fix proxy&account record')
@endsection


@section('script')
@include('loadchartfunction')
<script> @include('tools.fjs') </script>
@endsection


@section('content')
@include('fromtoform')
<div id="p_fix_container"> </div>
<div id="p_error"> </div>
<div id="a_fix_container"> </div>
<div id="a_error"> </div>
@endsection
