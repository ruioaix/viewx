@extends('index')

@section('title', 'Good or Wrong Accounts')
@endsection

@section('content')

<h2>Success: (recent 1000)</h2>
<canvas id="account_success" width="1140" height="400"></canvas>
<script type="text/javascript">
$(document).ready(function() {
    var ctx = document.getElementById("account_success").getContext("2d");
    new Chart(ctx).Bar({!! $sus !!}, {scaleLabel: function(object){return " " + object.value; }});
});
</script>

<h2>Cookies Error: (recent 1000)</h2>
<canvas id="account_cookies" width="1140" height="400"></canvas>
<script type="text/javascript">
$(document).ready(function() {
    var ctx = document.getElementById("account_cookies").getContext("2d");
    new Chart(ctx).Bar({!! $cookies !!}, {scaleLabel: function(object){return " " + object.value; }});
});
</script>

<h2>Return False: (recent 1000)</h2>
<canvas id="account_rf" width="1140" height="400"></canvas>
<script type="text/javascript">
$(document).ready(function() {
    var ctx = document.getElementById("account_rf").getContext("2d");
    new Chart(ctx).Bar({!! $lfalse !!}, {scaleLabel: function(object){return " " + object.value; }});
});
</script>

@endsection
