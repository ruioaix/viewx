$(document).ready(function() {
    $('#account_monitor').remove(); // this is my <canvas> element
    $('#canvas_container').append('<canvas id="account_monitor" width="1140" height="400"></canvas>');
    var ctx = document.getElementById("account_monitor").getContext("2d");
    new Chart(ctx).Line({!! $pm !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    $('#proxy_error_{{ $from_secord }}_{{ $to_secord }}').remove(); // this is my <canvas> element
    $('#canvas_container').prepend('<canvas id="proxy_error_{{ $from_secord }}_{{ $to_secord }}" width="1140" height="400"></canvas>');
    $('#proxy_monitor_{{ $from_secord }}_{{ $to_secord }}').remove(); // this is my <canvas> element
    $('#canvas_container').prepend('<canvas id="proxy_monitor_{{ $from_secord }}_{{ $to_secord }}" width="1140" height="400"></canvas>');
    $('#proxy_title_{{ $from_secord }}_{{ $to_secord }}').remove(); // this is my <canvas> element
    $('#canvas_container').prepend('<div id="proxy_title_{{ $from_secord }}_{{ $to_secord }}"></div>');
});
