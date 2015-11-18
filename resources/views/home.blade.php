@extends('index')

@section('title', 'Structure of Crawl System')
@endsection

@section('script')
<link href="css/jquery.orgchart.css" rel="stylesheet">
<script src="js/jquery.orgchart.min.js"></script>
@endsection

@section('content')
Download from <a href="https://github.com/xrfind/RXQ"> this page </a>, branch: mostly.

<h2> Dependences: </h2>
<ul id="run" class="hide">
<li> run <ul>
    <li> login <ul>
        <li> proxy <ul> 
            <li> proxydb </li> 
            <li> hidemyass </li> 
            <li> pachongorg </li> 
            <li> freeproxy listsnet </li> 
        </ul> </li>
        <li> account <ul> <li> accountdb </li> </ul> </li>
    </ul> </li>
    <li> task <ul> 
        <li> taskdb </li>
        <li> special <ul>
            <li> specialdb </li>
        </ul> </li>
    </ul> </li>
    <li> info <ul> 
        <li> infodb </li>
    </ul></li>
    <li> gain <ul> 
        <li> gaindb </li>
    </ul></li>
    <li> adjust <ul> 
        <li> adjustdb </li>
    </ul></li>
</ul></li>
</ul>
<div id="run-chart">
</div>
<script type="text/javascript">
$(document).ready(function() {
    $("#run").orgChart({container: $("#run-chart")});
});
</script>

@endsection

