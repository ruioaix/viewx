@extends('index')


@section('title', 'About')
@endsection

@section('script')
<link href="https://cdnjs.cloudflare.com/ajax/libs/vis/4.9.0/vis.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/vis/4.9.0/vis.min.js"></script>
<style type="text/css">
#mynetwork {
    width: 1140px;
    height: 600px;
}
</style>
@endsection

@section('content')
<div id="mynetwork"></div>

<script type="text/javascript">
// create an array with nodes
var nodes = new vis.DataSet([
    {id: 1, label: 'Node 1'},
    {id: 2, label: 'Node 2'},
    {id: 3, label: 'Node 3'},
    {id: 4, label: 'Node 4'},
    {id: 5, label: 'Node 5'}
]);

// create an array with edges
var edges = new vis.DataSet([
{from: 1, to: 3, arrows:'to, from'},
{from: 1, to: 2},
{from: 2, to: 4},
{from: 2, to: 5}
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
