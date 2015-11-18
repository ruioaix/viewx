$(document).ready(function() {

    $('#proxy_error_{{ $step }}').remove(); // this is my <canvas> element
    $('#canvas_container').prepend('<canvas id="proxy_error_{{ $step }}" width="1140" height="400"></canvas>');
    $('#proxy_monitor_{{ $step }}').remove(); // this is my <canvas> element
    $('#canvas_container').prepend('<canvas id="proxy_monitor_{{ $step }}" width="1140" height="400"></canvas>');
    $('#proxy_title_{{ $step }}').remove(); // this is my <canvas> element
    $('#canvas_container').prepend('<div id="proxy_title_{{ $step }}"></div>');

    document.getElementById("proxy_title_{{ $step }}").innerHTML = "<h3>Unit: {{ (int)($step/(24*3600)) }}D{{ (int)($step%(24*3600)/3600) }}H{{ (int)($step%3600)/60 }}M</h3>";

    var ctx = document.getElementById("proxy_monitor_{{ $step }}").getContext("2d");
    new Chart(ctx).Line({!! $pm !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});
    var ctx = document.getElementById("proxy_error_{{ $step }}").getContext("2d");
    new Chart(ctx).Bar({!! $pe !!}, {scaleLabel: function(object){return " " + object.value; }});

    document.getElementById("msg").innerHTML = "";
});
