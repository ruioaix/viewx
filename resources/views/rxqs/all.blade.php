@extends('index')

@section('title', 'RXQ Modules Relations')
@endsection

@section('script')
<link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.9.0/vis.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.9.0/vis.min.js"></script>
<script type="text/javascript"> @include('rxqs.js') </script>
<style type="text/css"> #mynetwork { width: 1140px; height: 600px; } </style>
<script type="text/javascript">
function loadchart(node) {
    document.getElementById("msg").innerHTML = "working...";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (xhttp.readyState == 4 && xhttp.status == 200) {
            eval(xhttp.responseText)
            document.getElementById("msg").innerHTML = "";
        }
    };
    var url = "{{ $url }}";
    xhttp.open("GET", url.concat('/').concat(node), true);
    xhttp.send();
}
</script>
@endsection

@section('content')
<button id='viewbutton' type="button" class="btn btn-primary" onclick="loadchart(-1)">SHOW ALL</button>
<span id="msg"></span>
<div id="mynetwork"></div>
@endsection
