@extends('index')

@section('script')
<link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.9.0/vis.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.9.0/vis.min.js"></script>
<style type="text/css">
#mynetwork {
    width: 1140px;
    height: 800px;
}
</style>
@endsection

@section('content')
<div id="mynetwork"></div>

<script type="text/javascript">
// create an array with nodes
var nodes = new vis.DataSet([
    @yield('dataset')
]);

// create an array with edges
var edges = new vis.DataSet([
    @yield('edges')
]);

// create a network
var container = document.getElementById('mynetwork');

// provide the data in the vis format
var data = {
    nodes: nodes,
    edges: edges
};
var options = {
    nodes: {
        shape: 'dot',
        size: 20,
        font: {
            size: 12,
            color: '#000'
        },
        borderWidth: 2
    },
    edges: {
        width: 2
    }
};

// initialize your network!
var network = new vis.Network(container, data, options);
</script>
@endsection
