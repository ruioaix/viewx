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

    document.getElementById("period").innerHTML = "<h2 id='period'>Period: {{ (int)(($step * $stepNum)/(24*3600)) }}D{{ (int)(($step * $stepNum)%(24*3600)/3600) }}H</h2>";

    document.getElementById("pachong_usage").innerHTML = "{{ $source_usage_rate['success'][10] }}/{{$source_usage_rate['all'][10] }} ({{ $source_usage_rate_exact['success'][10] }}/{{$source_usage_rate_exact['all'][10] }}) {{ $source_usage_rate['success'][10]/$source_usage_rate['all'][10] }}"
    document.getElementById("hidemyass_usage").innerHTML = "{{ $source_usage_rate['success'][9] }}/{{$source_usage_rate['all'][9] }} ({{ $source_usage_rate_exact['success'][9] }}/{{$source_usage_rate_exact['all'][9] }}) {{ $source_usage_rate['success'][9] / $source_usage_rate['all'][9] }}"
    document.getElementById("freeproxylists_usage").innerHTML = "{{ $source_usage_rate['success'][8] }}/{{$source_usage_rate['all'][8] }} ({{ $source_usage_rate_exact['success'][8] }}/{{$source_usage_rate_exact['all'][8] }}) {{ $source_usage_rate['success'][8] / $source_usage_rate['all'][8] }}"

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

    document.getElementById("msg").innerHTML = "";
});
