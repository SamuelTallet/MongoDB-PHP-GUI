
/**
 * Instance of vis.Network.
 * 
 * @type {object}
 */
MPG.visNetwork = null;

/**
 * Options of vis.Network.
 * 
 * @see https://ww3.arb.ca.gov/ei/tools/lib/vis/docs/network.html#Configuration_options
 * 
 * @type {object}
 */
MPG.visNetworkOptions = {

    width:  '100%',
    height: (window.innerHeight - 100) + 'px',
    nodes: {
        color: {
            background: "transparent",
            border: "transparent"
        }
    },
    edges: {
        width: 1,
        color: {
            color: '#ddd',
            highlight: '#0062cc'
        },
    }

};

/**
 * Forwards navigation links.
 * 
 * @returns {void}
 */
MPG.helpers.forwardNavLinks = function() {

    var fragmentUrl = window.location.hash.split('#');

    if ( fragmentUrl.length === 2 && fragmentUrl[1] !== '' ) {

        databaseAndCollectionName = fragmentUrl[1].split('/');

        if ( databaseAndCollectionName.length === 1 ) {

            MPG.helpers.completeNavLinks('#' + databaseAndCollectionName[0]);

        } else if ( databaseAndCollectionName.length === 2 ) {

            MPG.helpers.completeNavLinks(
                '#' + databaseAndCollectionName[0] + '/' + databaseAndCollectionName[1]
            );

        }

    }

};

/**
 * Draws vis.Network graph.
 * 
 * @returns {void}
 */
MPG.drawVisNetwork = function() {

    MPG.helpers.doAjaxRequest(
        'GET',
        MPG_BASE_URL + '/ajaxDatabaseGetNetworkGraph',
        function(response) {

            var visNetworkContainer = document.querySelector('#vis-network-container');
            var networkGraph = JSON.parse(response);

            MPG.visNetwork = new vis.Network(
                visNetworkContainer, networkGraph.visData, MPG.visNetworkOptions
            );

            MPG.visNetwork.on('select', function(nodeProperties) {

                if ( nodeProperties.nodes.length === 0 ) {
                    return;
                }

                var selectedNodeId = nodeProperties.nodes[0];
                var selectedNodeMapping = networkGraph.mapping[selectedNodeId];
                var targetUrl = MPG_BASE_URL + '/queryDatabase#';

                if ( selectedNodeMapping.databaseName !== null ) {

                    targetUrl += selectedNodeMapping.databaseName;

                    if ( selectedNodeMapping.collectionName !== null ) {
                        targetUrl += '/' + selectedNodeMapping.collectionName;
                    }

                    window.location.href = targetUrl;

                }


            });

        },
        null
    );

};

// When document is ready:
window.addEventListener('DOMContentLoaded', function(_event) {

    MPG.helpers.forwardNavLinks();
    MPG.eventListeners.addMenuToggle();
    
    MPG.drawVisNetwork();

});
