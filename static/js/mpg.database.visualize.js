
/**
 * MongoDB PHP GUI namespace.
 * 
 * @type {object}
 */
var MPG = {};

/**
 * vis.Network options.
 * 
 * @type {object}
 */
MPG.visNetworkOptions = {

    width:  '100%',
    height: '400px',
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
 * Helpers sub-namespace.
 * 
 * @type {object}
 */
MPG.helpers = {};

/**
 * Does an ajax request.
 * 
 * @param {string} method 
 * @param {string} url 
 * @param {function} successCallback 
 * @param {?string} body
 * 
 * @returns {void}
 */
MPG.helpers.doAjaxRequest = function(method, url, successCallback, body) {

    var xhr = new XMLHttpRequest();

    xhr.addEventListener('readystatechange', function() {

        if ( this.readyState === 4 ) {
            if ( this.status === 200 ) {
                successCallback(this.responseText);
            } else {
                window.alert('Error: ' + JSON.parse(this.responseText).error.message);
            }
        }

    });

    xhr.open(method, url);
    xhr.send(body);

};

/**
 * Event listeners sub-namespace.
 * 
 * @type {object}
 */
MPG.eventListeners = {};

/**
 * Adds an event listener on "Menu toggle" button.
 * 
 * @returns {void}
 */
MPG.eventListeners.addMenuToggle = function() {

    document.querySelector('#menu-toggle-button').addEventListener('click', function(_event) {
        document.querySelector('.navbar').classList.toggle('menu-expanded');
    });

};

/**
 * Draws vis.Network.
 */
MPG.drawVisNetwork = function() {

    MPG.helpers.doAjaxRequest(
        'GET',
        MPG_BASE_URL + '/ajaxDatabaseGetNetworkGraph',
        function(response) {

            var visNetworkContainer = document.querySelector('#vis-network-container');
            var networkGraph = JSON.parse(response);

            var visNetwork = new vis.Network(
                visNetworkContainer, networkGraph.visData, MPG.visNetworkOptions
            );

            visNetwork.on('select', function(nodeProperties) {

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

    MPG.eventListeners.addMenuToggle();
    MPG.drawVisNetwork();

});
