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

    var delay = (function(){
      var timer = 0;
      return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
      };
    })();

    $('#name').on('input', function() {
        delay(function(){
            var node = document.getElementById('name').value;
            if (node.length == 0) {
                return;
            }
            for (var nid in nodes['_data']) {
                for (var nd in nodes['_data'][nid]) {
                    if (nd == 'label') {
                        mname = nodes['_data'][nid]['label'];
                        if (node.length > 0 && mname.indexOf(node) > -1) {
                            nodes['_data'][nid]['color'] = '#FFFF00';
                        }
                        else {
                            nodes['_data'][nid]['color'] = '#90C1FE';
                        }
                    }
                }
            }
            network.destroy();
            data = { nodes: nodes, edges: edges };
            network = new vis.Network(container, data, options);
            network.on("doubleClick", function (params) {
                loadchart(params.nodes);
            });
        }, 500);
    });

});
