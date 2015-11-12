@extends('index')

@section('title', 'Proxy Analysis')
@endsection

@section('content')
<h2>Proxy Monitor: </h2>
<canvas id="myChart" width="1140" height="400"></canvas>
<script type="text/javascript">
$(document).ready(function() {
    var ctx = document.getElementById("myChart").getContext("2d");
    new Chart(ctx).Line({!! $data !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});
});
</script>


@endsection
