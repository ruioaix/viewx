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

    $('#all_chart').remove();
    $('#canvas_container').prepend('<canvas id="all_chart" width="1140" height="400"></canvas>');
    $('#all_title').remove();
    $('#canvas_container').prepend('<div id="all_title"></div>');

    $('#duration_chart').remove(); 
    $('#canvas_container').prepend('<canvas id="duration_chart" width="1140" height="400"></canvas>');
    $('#duration_title').remove(); 
    $('#canvas_container').prepend('<div id="duration_title"></div>');

    $('#pa_chart').remove(); 
    $('#canvas_container').prepend('<canvas id="pa_chart" width="1140" height="400"></canvas>');
    $('#pa_title').remove(); 
    $('#canvas_container').prepend('<div id="pa_title"></div>');

    document.getElementById("period").innerHTML = "<h3>From {{ (int)($from_secord/(24*3600)) }}D{{ (int)($from_secord%(24*3600)/3600) }}H to {{ (int)($to_secord/(24*3600)) }}D{{ (int)($to_secord%(24*3600)/3600) }}H, Unit: {{ (int)(($from_secord - $to_secord)/3600/60) }}H{{ (int)(($from_secord - $to_secord)/60%3600/60) }}M</h3>";

    document.getElementById("current").innerHTML = "Proxy Hits: {{ $wintry }}%, Account Hits: {{ $awintry }}%, Average aliving duration: {{ $avet }} <ol> @foreach ($living as $ipport => $ut ) <li> {{ $ipport }} & {{ $ut['username'] }} => {{ $ut['time'] }} </li> @endforeach </ol> ";

    document.getElementById("pa_title").innerHTML = "<h3>Proxy & Account</h3>";
    var ctx = document.getElementById("pa_chart").getContext("2d");
    new Chart(ctx).Line({!! $pa !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2, multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"});

    document.getElementById("duration_title").innerHTML = "<h3>Duration</h3>";
    var ctx = document.getElementById("duration_chart").getContext("2d");
    new Chart(ctx).Line({!! $duration !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("all_title").innerHTML = "<h3>All Errors:</h3>";
    var ctx = document.getElementById("all_chart").getContext("2d");
    new Chart(ctx).Line({!! $codedists['all'] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("ten_minute_title").innerHTML = "<h3>Connections aborted under 5m:</h3>";
    var ctx = document.getElementById("ten_minute_chart").getContext("2d");
    new Chart(ctx).Line({!! $codedists[300] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("one_hour_title").innerHTML = "<h3>Connection aborted between 5 minutes and 1 hour :</h3>";
    var ctx = document.getElementById("one_hour_chart").getContext("2d");
    new Chart(ctx).Line({!! $codedists[3600] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("four_hour_title").innerHTML = "<h3>Connection aborted between 1 hour and 4 hours:</h3>";
    var ctx = document.getElementById("four_hour_chart").getContext("2d");
    new Chart(ctx).Line({!! $codedists[14400] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("more_four_hour_title").innerHTML = "<h3>Connection keep more than 4 hours:</h3>";
    var ctx = document.getElementById("more_four_hour_chart").getContext("2d");
    new Chart(ctx).Line({!! $codedists['MORE'] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("msg").innerHTML = "";
});
