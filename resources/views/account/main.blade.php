@extends('index')

@section('title', 'XUEQIU Account')
@endsection

@section('content')

<script type="text/javascript">
function loadDoc() {
    var step = document.getElementById('step').value
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            eval(xhttp.responseText)
        }
    };
    var url = "{{ action('AccountController@step', ['']) }}";
    xhttp.open("GET", url.concat('/').concat(step), true);
    xhttp.send();
}
</script>

<form class="form-inline">
<input class="search form-control" placeholder="Search" id="step"/>
<button type="button" class="btn btn-primary" onclick="loadDoc()">VIEW</button>
</form>

<div id="canvas_container">
<h2>Xueqiu Account Monitor: (50 hours)</h2>
<canvas id="account_monitor" width="1140" height="400"></canvas>
</div>

<h2>Xueqiu Account Error Type: (50 hours)</h2>
<canvas id="error" width="1140" height="400"></canvas>

@endsection
