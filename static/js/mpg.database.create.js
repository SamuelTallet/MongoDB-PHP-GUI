
/**
 * MongoDB PHP GUI namespace.
 * 
 * @type {object}
 */
var MPG = {};

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
 * Creates database/collection.
 * 
 * @returns {void}
 */
MPG.createDatabase = function() {

    var databaseName = window.prompt('Database name to create or use');
    if ( databaseName === null ) {
        return window.location = '/';
    }
    
    var collectionName = window.prompt('Collection name to create');
    if ( collectionName === null ) {
        return window.location = '/';
    }

    MPG.helpers.doAjaxRequest(
        'GET',
        '/ajax/database/' + databaseName + '/createCollection/' + collectionName,
        function(_response) {
            window.location = '/';
        },
        null
    );

};

// When document is ready:
window.addEventListener('DOMContentLoaded', function(_event) {

    MPG.createDatabase();

});
