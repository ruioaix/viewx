@extends('index')

@section('title', 'Fix proxy&account record')
@endsection


@section('script')
@include('loadchartfunction')
<script> @include('tools.fjs') </script>
<script type="text/javascript">
function fixpar() {
    var fromh = document.getElementById('fromhour').value;
    var toh = document.getElementById('tohour').value;
    if (fromh == '') fromh = 0;
    else fromh = parseInt(fromh);
    if (toh == '') toh = 0;
    else toh = parseInt(toh);
    if (!isNaN(fromh) && !isNaN(toh) && toh >= 0 && fromh > toh) {
        document.getElementById("msg").innerHTML = "working...";
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (xhttp.readyState == 4 && xhttp.status == 200) {
                eval(xhttp.responseText)
            }
        };
        var url = "{{ action('ToolsController@fstepS', ['']) }}";
        xhttp.open("GET", url.concat('/').concat(fromh).concat('-').concat(toh), true);
        xhttp.send();
    }
    else {
        document.getElementById("msg").innerHTML = "<strong> Error</strong>";
    }
}
</script>
@endsection


@section('content')
<form class="form-inline">
  <div class="form-group">
     <div class="input-group">
      <div class="input-group-addon">Period: from</div>
      <input type="text" class="form-control" id="fromhour" value="60">
      <div class="input-group-addon">hours ago</div>
     </div>
     <div class="input-group">
      <div class="input-group-addon">to</div>
      <input type="text" class="form-control" id="tohour" placeholder="0">
      <div class="input-group-addon">hours ago</div>
     </div>
  </div>
  <button id='viewbutton' type="button" class="btn btn-default" onclick="loadchart()">VIEW</button>
  <button id='fixbutton' type="button" class="btn btn-danger" onclick="fixpar()">FIX</button>
  <span class="" id="msg"> </span>
</form>

<div id="p_fix_container"> </div>
<div id="p_error"> </div>
<div id="a_fix_container"> </div>
<div id="a_error"> </div>
@endsection
