@extends('index')

@section('title', 'Structure of Crawl System')
@endsection

@section('script')
<link href="css/jquery.orgchart.css" rel="stylesheet">
<script src="js/jquery.orgchart.min.js"></script>
@endsection

@section('content')
<ul id="login" class="hide">
</ul>

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
        <li> httpbase </li>
    </ul></li>
</ul></li>
</ul>

<ul id="db" class="hide">
    <li> db <ul>
        <li> accountdb </li>
        <li> proxydb </li>
        <li> taskdb </li>
        <li> gaindb </li>
        <li> adjustdb </li>
        <li> specialdb </li>
        <li> taskdb </li>
        <li> variables </li>
    </ul></li>
</ul>

<ul id="default" class="hide">
    <li> default <ul>
        <li> account </li>
        <li> accountdb </li>
        <li> adjustdb </li>
        <li> db </li>
        <li> gaindb </li>
        <li> hidemyass </li>
        <li> infodb </li>
        <li> proxydb </li>
        <li> run </li>
        <li> specialdb </li>
        <li> taskdb </li>
        <li> variables </li>
    </ul></li>
</ul>


<div id="run-chart">
</div>

<div id="login-chart">
</div>

<div id="db-chart">
</div>

<div id="default-chart">
</div>

<script type="text/javascript">
$(document).ready(function() {
    $("#login").orgChart({container: $("#login-chart")});
    $("#run").orgChart({container: $("#run-chart")});
    $("#db").orgChart({container: $("#db-chart")});
    $("#default").orgChart({container: $("#default-chart")});
});
</script>

@endsection

