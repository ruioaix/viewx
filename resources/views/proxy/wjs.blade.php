$(document).ready(function() {
    $('#hidemyass_chart').remove(); 
    $('#canvas_container').prepend('<canvas id="hidemyass_chart" width="1140" height="400"></canvas>');
    $('#hidemyass_title').remove(); 
    $('#canvas_container').prepend('<div id="hidemyass_title"></div>');

    $('#freeproxylists_chart').remove(); 
    $('#canvas_container').prepend('<canvas id="freeproxylists_chart" width="1140" height="400"></canvas>');
    $('#freeproxylists_title').remove(); 
    $('#canvas_container').prepend('<div id="freeproxylists_title"></div>');

    $('#pachong_chart').remove(); 
    $('#canvas_container').prepend('<canvas id="pachong_chart" width="1140" height="400"></canvas>');
    $('#pachong_title').remove(); 
    $('#canvas_container').prepend('<div id="pachong_title"></div>');

    $('#period').remove(); 
    $('#canvas_container').prepend('<h3 id="period">Period: {{ (int)(($step * $stepNum)/(24*3600)) }}D{{ (int)(($step * $stepNum)%(24*3600)/3600) }}H</h3>');

    document.getElementById("pachong_title").innerHTML = "<h3><a href='http://pachong.org'>Pachong.org</a></h3>";
    var ctx = document.getElementById("pachong_chart").getContext("2d");
    new Chart(ctx).Line({!! $res_a[10] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("hidemyass_title").innerHTML = "<h3><a href='http://proxylist.hidemyass.com/'> Hidemyass Subscription</a></h3>";
    var ctx = document.getElementById("hidemyass_chart").getContext("2d");
    new Chart(ctx).Line({!! $res_a[9] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("freeproxylists_title").innerHTML = "<h3><a href='http://freeproxylists.net'>freeproxylists.net</a></h3>";
    var ctx = document.getElementById("freeproxylists_chart").getContext("2d");
    new Chart(ctx).Line({!! $res_a[8] !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});

    document.getElementById("msg").innerHTML = "";
});
