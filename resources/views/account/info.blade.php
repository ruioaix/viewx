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

<h1> {{ $un }} </h1>
<canvas id="info" width="440" height="440"></canvas>
<script type="text/javascript">
$(document).ready(function() {
    var ctx = document.getElementById("info").getContext("2d");
    new Chart(ctx).Radar({!! $res !!});
});
</script>
