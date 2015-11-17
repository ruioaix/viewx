$(document).ready(function() {
    $('#account_monitor').remove(); // this is my <canvas> element
    $('#canvas_container').append('<canvas id="account_monitor" width="1140" height="400"></canvas>');
    var ctx = document.getElementById("account_monitor").getContext("2d");
    new Chart(ctx).Line({!! $pm !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});
});

$(document).ready(function() {
    var ctx = document.getElementById("error").getContext("2d");
    new Chart(ctx).Bar({!! $pe !!}, {scaleLabel: function(object){return " " + object.value; }});
});
