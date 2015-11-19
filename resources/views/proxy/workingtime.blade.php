@extends('index')


@section('title', 'Proxy WrokingTime Monitor')
@endsection


@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
<script type="text/javascript">
function loadchart() {
    var day = document.getElementById('day').value;
    var hour = document.getElementById('hour').value;
    if (day == '') day = 0;
    else day = parseInt(day);
    if (hour == '') hour = 0;
    else hour = parseInt(hour);
    if (hour >= 0 && day >= 0 && hour + day > 0) {
        document.getElementById("msg").innerHTML = "working...";
        var time = hour * 60 + day * 60 * 24;
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                eval(xhttp.responseText)
            }
        };
        var url = "{{ action('ProxyController@wstep', ['']) }}";
        xhttp.open("GET", url.concat('/').concat(time), true);
        xhttp.send();
    }
    else {
        document.getElementById("msg").innerHTML = "<strong>Error</strong>";
    }
}
$(document).ready(function() {
    document.getElementById("period").innerHTML = "Period: {{ (int)(($step * $stepNum)/(24*3600)) }}D{{ (int)(($step * $stepNum)%(24*3600)/3600) }}H"
    document.getElementById("pachong_title").innerHTML = "<h3><a href='http://pachong.org'>Pachong.org</a></h3>";
    var ctx = document.getElementById("pachong_chart").getContext("2d");
    new Chart(ctx).Line({!! $res_a[10] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("hidemyass_title").innerHTML = "<h3><a href='http://proxylist.hidemyass.com/'> Hidemyass Subscription</a></h3>";
    var ctx = document.getElementById("hidemyass_chart").getContext("2d");
    new Chart(ctx).Line({!! $res_a[9] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("freeproxylists_title").innerHTML = "<h3><a href='http://freeproxylists.net'>freeproxylists.net</a></h3>";
    var ctx = document.getElementById("freeproxylists_chart").getContext("2d");
    new Chart(ctx).Line({!! $res_a[8] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});
});
</script>
@endsection


@section('content')

<form class="form-inline">
  <div class="form-group">
    <label for="form">How long you want to view? : </label>
    <div class="input-group">
      <input type="text" class="form-control" id="day" placeholder="0">
      <div class="input-group-addon"> days </div>
    </div>
    <div class="input-group">
      <input type="text" class="form-control" id="hour" placeholder="0">
      <div class="input-group-addon"> hours </div>
    </div>
  </div>
  <button id='viewbutton' type="button" class="btn btn-primary" onclick="loadchart()">VIEW</button>
  <span class="" id="msg"> </span>
</form>

<div id="canvas_container">
    <h3 id='period'> </h3>
    <div id="pachong_title"></div>
    <canvas id="pachong_chart" width="1140" height="400"></canvas>
    <div id="hidemyass_title"></div>
    <canvas id="hidemyass_chart" width="1140" height="400"></canvas>
    <div id="freeproxylists_title"></div>
    <canvas id="freeproxylists_chart" width="1140" height="400"></canvas>
</div>

@endsection


