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

    $('#account_chart').remove(); 
    $('#canvas_container').prepend('<canvas id="account_chart" width="1140" height="400"></canvas>');
    $('#account_title').remove(); 
    $('#canvas_container').prepend('<div id="account_title"></div>');

    document.getElementById("period").innerHTML = "<h2 id='period'>From {{ (int)($from_secord/(24*3600)) }}D{{ (int)($from_secord%(24*3600)/3600) }}H to {{ (int)($to_secord/(24*3600)) }}D{{ (int)($to_secord%(24*3600)/3600) }}H</h2>";

    document.getElementById("usage").innerHTML = "{{ $usage }} @foreach ($aliving as $ipport => $time) <li> {{ $ipport }} => {{ $time }} </li> @endforeach "

    document.getElementById("account_title").innerHTML = "<h3>Health</h3>";
    var ctx = document.getElementById("account_chart").getContext("2d");
    new Chart(ctx).Line({!! $res !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

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
