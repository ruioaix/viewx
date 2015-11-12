@extends('index')

@section('title', 'Proxy')
@endsection

@section('content')

<h2>Proxy Monitor: (50 hours)</h2>
<canvas id="proxy_monitor" width="1140" height="400"></canvas>
<script type="text/javascript">
$(document).ready(function() {
    var ctx = document.getElementById("proxy_monitor").getContext("2d");
    new Chart(ctx).Line({!! $pm !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});
});
</script>

<h2>Proxy Error Type: (50 hours)</h2>
<canvas id="proxy_error" width="1140" height="400"></canvas>
<script type="text/javascript">
$(document).ready(function() {
    var ctx = document.getElementById("proxy_error").getContext("2d");
    new Chart(ctx).Bar({!! $pe !!}, {scaleLabel: function(object){return " " + object.value; }});
});
</script>

@endsection
