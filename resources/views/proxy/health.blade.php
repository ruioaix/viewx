@extends('index')


@section('title', 'Proxy Health Monitor')
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
        var url = "{{ action('ProxyController@hstep', ['']) }}";
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

    document.getElementById("ten_minute_title").innerHTML = "<h3>Errors happened when connection closed under 5 minutes:</h3>";
    var ctx = document.getElementById("ten_minute_chart").getContext("2d");
    new Chart(ctx).Line({!! $code_res_a[300] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("one_hour_title").innerHTML = "<h3>Errors happened when connection closed between 5 minutes and 1 hour:</h3>";
    var ctx = document.getElementById("one_hour_chart").getContext("2d");
    new Chart(ctx).Line({!! $code_res_a[3600] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("four_hour_title").innerHTML = "<h3>Errors happened when connection closed between 1 hour and 4 hours:</h3>";
    var ctx = document.getElementById("four_hour_chart").getContext("2d");
    new Chart(ctx).Line({!! $code_res_a[14400] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("more_four_hour_title").innerHTML = "<h3>Errors happened when connection keep more than 4 hours:</h3>";
    var ctx = document.getElementById("more_four_hour_chart").getContext("2d");
    new Chart(ctx).Line({!! $code_res_a['MORE'] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});
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


<h2 id='period'> </h2>

<h3> Hits </h3>
<div id='source_usage'> 
<a href='http://pachong.org'>Pachong.org</a> : <strong id='pachong_usage'>{{ $source_usage_rate['success'][10] }}/{{$source_usage_rate['all'][10] }} ({{ $source_usage_rate_exact['success'][10] }}/{{$source_usage_rate_exact['all'][10] }}) {{ $source_usage_rate['success'][10]/$source_usage_rate['all'][10] }}</strong><br/>
<a href='http://proxylist.hidemyass.com/'>hidemyass subscription</a> : <strong id='hidemyass_usage'>{{ $source_usage_rate['success'][9] }}/{{$source_usage_rate['all'][9] }} ({{ $source_usage_rate_exact['success'][9] }}/{{$source_usage_rate_exact['all'][9] }}) {{ $source_usage_rate['success'][9] / $source_usage_rate['all'][9] }}</strong><br/>
<a href='freeproxylists.net'>freeproxylists.net</a> : <strong id='freeproxylists_usage'>{{ $source_usage_rate['success'][8] }}/{{$source_usage_rate['all'][8] }} ({{ $source_usage_rate_exact['success'][8] }}/{{$source_usage_rate_exact['all'][8] }}) {{ $source_usage_rate['success'][8] / $source_usage_rate['all'][8] }}</strong> <br/>
</div>

<div id="canvas_container">
    <div id="pachong_title"></div>
    <canvas id="pachong_chart" width="1140" height="400"></canvas>
    <div id="hidemyass_title"></div>
    <canvas id="hidemyass_chart" width="1140" height="400"></canvas>
    <div id="freeproxylists_title"></div>
    <canvas id="freeproxylists_chart" width="1140" height="400"></canvas>
    <div id="ten_minute_title"></div>
    <canvas id="ten_minute_chart" width="1140" height="400"></canvas>
    <div id="one_hour_title"></div>
    <canvas id="one_hour_chart" width="1140" height="400"></canvas>
    <div id="four_hour_title"></div>
    <canvas id="four_hour_chart" width="1140" height="400"></canvas>
    <div id="more_four_hour_title"></div>
    <canvas id="more_four_hour_chart" width="1140" height="400"></canvas>
</div>

@endsection


