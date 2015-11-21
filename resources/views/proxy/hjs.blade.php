$(document).ready(function() {
    $('#more_four_hour_chart').remove();
    $('#canvas_container').prepend('<canvas id="more_four_hour_chart" width="1140" height="400"></canvas>');
    $('#more_four_hour_title').remove();
    $('#canvas_container').prepend('<div id="more_four_hour_title"></div>');
    $('#four_hour_chart').remove();
    $('#canvas_container').prepend('<canvas id="four_hour_chart" width="1140" height="400"></canvas>');
    $('#four_hour_title').remove();
    $('#canvas_container').prepend('<div id="four_hour_title"></div>');
    $('#one_hour_chart').remove();
    $('#canvas_container').prepend('<canvas id="one_hour_chart" width="1140" height="400"></canvas>');
    $('#one_hour_title').remove();
    $('#canvas_container').prepend('<div id="one_hour_title"></div>');
    $('#ten_minute_chart').remove();
    $('#canvas_container').prepend('<canvas id="ten_minute_chart" width="1140" height="400"></canvas>');
    $('#ten_minute_title').remove();
    $('#canvas_container').prepend('<div id="ten_minute_title"></div>');

    $('#freeproxylists_chart').remove(); 
    $('#canvas_container').prepend('<canvas id="freeproxylists_chart" width="1140" height="400"></canvas>');
    $('#freeproxylists_title').remove(); 
    $('#canvas_container').prepend('<div id="freeproxylists_title"></div>');

    $('#hidemyass_chart').remove(); 
    $('#canvas_container').prepend('<canvas id="hidemyass_chart" width="1140" height="400"></canvas>');
    $('#hidemyass_title').remove(); 
    $('#canvas_container').prepend('<div id="hidemyass_title"></div>');

    $('#pachong_chart').remove(); 
    $('#canvas_container').prepend('<canvas id="pachong_chart" width="1140" height="400"></canvas>');
    $('#pachong_title').remove(); 
    $('#canvas_container').prepend('<div id="pachong_title"></div>');

    document.getElementById("period").innerHTML = "<h2 id='period'>From {{ (int)($from_secord/(24*3600)) }}D{{ (int)($from_secord%(24*3600)/3600) }}H to {{ (int)($to_secord/(24*3600)) }}D{{ (int)($to_secord%(24*3600)/3600) }}H</h2>";

    document.getElementById("pachong_usage").innerHTML = "{{ $usage['pachong'] }} @foreach ($aliving['pachong'] as $ipport => $time) <li> {{ $ipport }} => {{ $time }} </li> @endforeach "
    document.getElementById("hidemyass_usage").innerHTML = "{{ $usage['hidemyass'] }} @foreach ($aliving['hidemyass'] as $ipport => $time) <li> {{ $ipport }} => {{ $time }} </li> @endforeach "
    document.getElementById("freeproxylists_usage").innerHTML = "{{ $usage['freeproxylists'] }} @foreach ($aliving['freeproxylists'] as $ipport => $time) <li> {{ $ipport }} => {{ $time }} </li> @endforeach "

    document.getElementById("pachong_title").innerHTML = "<h3><a href='http://pachong.org'>Pachong.org</a></h3>";
    var ctx = document.getElementById("pachong_chart").getContext("2d");
    new Chart(ctx).Line({!! $res_a['pachong'] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("hidemyass_title").innerHTML = "<h3><a href='http://proxylist.hidemyass.com/'> Hidemyass Subscription</a></h3>";
    var ctx = document.getElementById("hidemyass_chart").getContext("2d");
    new Chart(ctx).Line({!! $res_a['hidemyass'] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("freeproxylists_title").innerHTML = "<h3><a href='http://freeproxylists.net'>freeproxylists.net</a></h3>";
    var ctx = document.getElementById("freeproxylists_chart").getContext("2d");
    new Chart(ctx).Line({!! $res_a['freeproxylists'] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("ten_minute_title").innerHTML = "<h3>Connections aborted under 5m:</h3>";
    var ctx = document.getElementById("ten_minute_chart").getContext("2d");
    new Chart(ctx).Line({!! $code_res_a[300] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("one_hour_title").innerHTML = "<h3>Connection aborted between 5 minutes and 1 hour :</h3>";
    var ctx = document.getElementById("one_hour_chart").getContext("2d");
    new Chart(ctx).Line({!! $code_res_a[3600] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("four_hour_title").innerHTML = "<h3>Connection aborted between 1 hour and 4 hours:</h3>";
    var ctx = document.getElementById("four_hour_chart").getContext("2d");
    new Chart(ctx).Line({!! $code_res_a[14400] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("more_four_hour_title").innerHTML = "<h3>Connection keep more than 4 hours:</h3>";
    var ctx = document.getElementById("more_four_hour_chart").getContext("2d");
    new Chart(ctx).Line({!! $code_res_a['MORE'] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("msg").innerHTML = "";
});
