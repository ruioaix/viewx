@extends('index')

@section('title', 'XUEQIU Account')
@endsection

@section('content')

<h2>Xueqiu Account Monitor: (50 hours)</h2>
<canvas id="account_monitor" width="1140" height="400"></canvas>
<script type="text/javascript">
$(document).ready(function() {
    var ctx = document.getElementById("account_monitor").getContext("2d");
    new Chart(ctx).Line({!! $pm !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});
});
</script>

<h2>Xueqiu Account Error Type: (50 hours)</h2>
<canvas id="error" width="1140" height="400"></canvas>
<script type="text/javascript">
$(document).ready(function() {
    var ctx = document.getElementById("error").getContext("2d");
    new Chart(ctx).Bar({!! $pe !!}, {scaleLabel: function(object){return " " + object.value; }});
});
</script>

@endsection
