@extends('index')

@section('title', 'XUEQIU Adjust')
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
@endsection


@section('content')

<h2>Xueqiu Adjust Monitor: (50 hours)</h2>
<canvas id="xq_adj" width="1140" height="400"></canvas>
<script type="text/javascript">
$(document).ready(function() {
    var ctx = document.getElementById("xq_adj").getContext("2d");
    new Chart(ctx).Line({!! $adj !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});
});
</script>

@endsection
