$(document).ready(function() {

    $('.vis-network').remove();

    var nodes = new vis.DataSet([ @foreach ($nodes as $value) {!! $value !!} @endforeach ]);

    var edges = new vis.DataSet([ @foreach ($relations as $key => $value) {!! $value !!} @endforeach ]);

    var container = document.getElementById('mynetwork');

    var data = { nodes: nodes, edges: edges };
    var options = {
        nodes: {
            shape: 'dot',
            size: 20,
            font: { size: 12, color: '#000' },
            borderWidth: 2
        },
        edges: {
            width: 2
        },
@if (isset($node))
        layout: {
            hierarchical: {
                direction: 'UD',
                sortMethod: 'directed'
            }
        }
@endif
    };

    var network = new vis.Network(container, data, options);
    network.on("doubleClick", function (params) {
        loadchart(params.nodes)
    });
});
