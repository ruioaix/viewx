@extends('index')

@section('title', 'XUEQIU Account')
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.js"></script>
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
@endsection

@section('content')
<form class="form-inline">
  <div class="form-group">
    <label for="form">How long you want to view? : </label>
    <div class="input-group">
      <input type="text" class="form-control" id="day" placeholder="0">
      <div class="input-group-addon"> days </div>
    </div>
    <div class="input-group">
      <input type="text" class="form-control" id="hour" placeholder="0">
      <div class="input-group-addon"> hours </div>
    </div>
  </div>
  <button id='viewbutton' type="button" class="btn btn-primary" onclick="loadchart()">VIEW</button>
  <span class="" id="msg"> </span>
</form>
<h2 id='period'> </h2>


<form class="form-inline">
<input class="search form-control" placeholder="Search" id="step"/>
<button type="button" class="btn btn-primary" onclick="loadDoc()">VIEW</button>
</form>
<h2 id='period'> </h2>

<div id="canvas_container">
<h2>Xueqiu Account Monitor: (50 hours)</h2>
<canvas id="account_monitor" width="1140" height="400"></canvas>
</div>

<h2>Xueqiu Account Error Type: (50 hours)</h2>
<canvas id="error" width="1140" height="400"></canvas>

@endsection
