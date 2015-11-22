<style> 
.mfp-bg {
    background-color: #fff;    
    opacity: 0.8;
}
.mfp-content {
    text-align: center;
    width: auto;
}
</style>

<h1> {{ $username }} </h1>
<canvas id="info" width="1140" height="440"></canvas>
<script type="text/javascript">
$(document).ready(function() {
    var ctx = document.getElementById("info").getContext("2d");
    new Chart(ctx).Line({!! $res !!}, {scaleLabel: function(object){return " " + object.value; }, pointHitDetectionRadius : 2});
});
</script>
