@extends('index')

@section('title', $title)
@endsection

@section('script')
<link href="{{ action('MainController@index') }}/css/jquery.jsonview.css" rel="stylesheet">
<script src="{{ action('MainController@index') }}/js/jquery.jsonview.js"></script>
<script> 
$(document).ready(function() {
    $(function() {
        $("#err").JSONView({!! $res !!}, { collapsed: false});
        $("#origin").JSONView({!! $origin_json !!}, { collapsed: true });
        $("#new").JSONView({!! $new_json !!}, { collapsed: true });
    });
});
</script> 
@endsection

@section('content')
<h2> {{ $zid }} </h2>
<div class="col-md-4"> <h3> Differences: </h3> <div id='err'> </div> </div>
<div class="col-md-4"> <h3> Origin: </h3> <div id="origin"> </div></div>
<div class="col-md-4"> <h3> Now: </h3> <div id='new'> </div></div>
</div>
@endsection
