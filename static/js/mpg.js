
/**
 * MongoDB PHP GUI namespace.
 * 
 * @type {object}
 */
var MPG = {};

/**
 * Name of current database.
 * 
 * @type {string}
 */
MPG.databaseName = '';

/**
 * Name of current collection.
 * 
 * @type {string}
 */
MPG.collectionName = '';

/**
 * Does an ajax request.
 * 
 * @param {string} method 
 * @param {string} url 
 * @param {function} callback 
 * @param {?string} body
 * 
 * @returns {void}
 */
MPG.doAjaxRequest = function(method, url, callback, body) {

    var xhr = new XMLHttpRequest();

    xhr.addEventListener('readystatechange', function() {
        if ( this.readyState === 4 ) {
            callback(this.responseText);
        }
    });

    xhr.open(method, url);
    xhr.send(body);

};

/**
 * Reloads collections of a specific database.
 * 
 * @param {string} databaseName
 * 
 * @returns {void}
 */
MPG.reloadCollections = function(databaseName) {

    MPG.doAjaxRequest(
        'GET', '/ajax/database/' + databaseName + '/listCollections', function(response) {

            var collectionsList = document.querySelector('#mpg-collections-list');

            collectionsList.innerHTML = '';
            MPG.collectionName = '';

            JSON.parse(response).forEach(function(collectionName) {

                collectionsList.innerHTML +=
                    '<li class="collection-name">'
                        + '<i class="fa fa-file-text" aria-hidden="true"></i> '
                        + '<a class="mpg-collection-link" '
                        + 'data-collection-name="' + collectionName
                        + '" href="#' + MPG.databaseName + '/' + collectionName + '">'
                        + collectionName
                        + '</a>'
                    + '</li>';
                
            });

            MPG.eventListeners.addCollections();

        }, null
    );

};

/**
 * Event listeners sub-namespace.
 * 
 * @type {object}
 */
MPG.eventListeners = {};

/**
 * Adds an event listener on each database.
 * 
 * @returns {void}
 */
MPG.eventListeners.addDatabases = function() {

    document.querySelectorAll('.mpg-database-link').forEach(function(databaseLink) {

        databaseLink.addEventListener('click', function(_event) {
            
            MPG.databaseName = databaseLink.dataset.databaseName;
            MPG.reloadCollections(databaseLink.dataset.databaseName);

        });

    });

};

/**
 * Adds an event listener on each collection.
 * 
 * @returns {void}
 */
MPG.eventListeners.addCollections = function() {

    document.querySelectorAll('.mpg-collection-link').forEach(function(collectionLink) {

        collectionLink.addEventListener('click', function(_event) {
            
            MPG.collectionName = collectionLink.dataset.collectionName;

        });

    });

};

/**
 * Adds an event listener on "Create" button.
 * 
 * @returns {void}
 */
MPG.eventListeners.addCreate = function() {

    document.querySelector('#mpg-create-button').addEventListener('click', function(_event) {

        var databaseName = window.prompt('Database name to create or use', MPG.databaseName);
        if ( databaseName === null ) {
            return;
        }
        
        var collectionName = window.prompt('Collection name to create');
        if ( collectionName === null ) {
            return;
        }

        MPG.doAjaxRequest(
            'GET',
            '/ajax/database/' + databaseName + '/createCollection/' + collectionName,
            function(response) {
                window.location.reload();
            },
            null
        );

    });

};

/**
 * Adds an event listener on "Insert one" button.
 * 
 * @returns {void}
 */
MPG.eventListeners.addInsertOne = function() {

    document.querySelector('#mpg-insert-one-button').addEventListener('click', function(_event) {

        if ( MPG.databaseName === '' || MPG.collectionName === '' ) {
            return window.alert('Please select a database and a collection.');
        }

        var requestBody = {};
        var filterOrDocTextAreaValue = document.querySelector('#mpg-filter-or-doc-textarea').value;

        if ( filterOrDocTextAreaValue === '' ) {
            return window.alert('Please fill the document text area.');
        }
        
        requestBody.document = JSON.parse(filterOrDocTextAreaValue);
        
        MPG.doAjaxRequest(
            'POST',
            '/ajax/database/' + MPG.databaseName + '/collection/'
                + MPG.collectionName + '/insertOne',
            function(response) {

                var outputCode = document.querySelector('#mpg-output-code');
                outputCode.innerHTML = 'Inserted: ' + response;

            },
            JSON.stringify(requestBody)
        );

    });

};

/**
 * Adds an event listener on "Count" button.
 * 
 * @returns {void}
 */
MPG.eventListeners.addCount = function() {

    document.querySelector('#mpg-count-button').addEventListener('click', function(_event) {

        if ( MPG.databaseName === '' || MPG.collectionName === '' ) {
            return window.alert('Please select a database and a collection.');
        }

        var requestBody = {};
        var filterOrDocTextAreaValue = document.querySelector('#mpg-filter-or-doc-textarea').value;

        if ( filterOrDocTextAreaValue === '' ) {
            requestBody.filter = {};
        } else {
            requestBody.filter = JSON.parse(filterOrDocTextAreaValue);
        }

        MPG.doAjaxRequest(
            'POST',
            '/ajax/database/' + MPG.databaseName + '/collection/'
                + MPG.collectionName + '/count',
            function(response) {

                var outputCode = document.querySelector('#mpg-output-code');
                outputCode.innerHTML = 'Count: ' + response;

            },
            JSON.stringify(requestBody)
        );

    });

};

/**
 * Adds an event listener on "Delete one" button.
 * 
 * @returns {void}
 */
MPG.eventListeners.addDeleteOne = function() {

    document.querySelector('#mpg-delete-one-button').addEventListener('click', function(_event) {

        if ( MPG.databaseName === '' || MPG.collectionName === '' ) {
            return window.alert('Please select a database and a collection.');
        }

        var requestBody = {};
        var filterOrDocTextAreaValue = document.querySelector('#mpg-filter-or-doc-textarea').value;

        if ( filterOrDocTextAreaValue === '' ) {
            return window.alert('Please fill the filter text area.');
        }
        
        requestBody.filter = JSON.parse(filterOrDocTextAreaValue);
        
        MPG.doAjaxRequest(
            'POST',
            '/ajax/database/' + MPG.databaseName + '/collection/'
                + MPG.collectionName + '/deleteOne',
            function(response) {

                var outputCode = document.querySelector('#mpg-output-code');
                outputCode.innerHTML = 'Deleted: ' + response;

            },
            JSON.stringify(requestBody)
        );

    });

};

/**
 * Adds an event listener on "Find" button.
 * 
 * @returns {void}
 */
MPG.eventListeners.addFind = function() {

    document.querySelector('#mpg-find-button').addEventListener('click', function(_event) {

        if ( MPG.databaseName === '' || MPG.collectionName === '' ) {
            return window.alert('Please select a database and a collection.');
        }

        var requestBody = {};
        var filterOrDocTextAreaValue = document.querySelector('#mpg-filter-or-doc-textarea').value;

        if ( filterOrDocTextAreaValue === '' ) {
            requestBody.filter = {};
        } else {
            requestBody.filter = JSON.parse(filterOrDocTextAreaValue);
        }

        requestBody.options = {};
        requestBody.options.limit = parseInt(document.querySelector('#mpg-limit-input').value);

        MPG.doAjaxRequest(
            'POST',
            '/ajax/database/' + MPG.databaseName + '/collection/'
                + MPG.collectionName + '/find',
            function(response) {

                var outputCode = document.querySelector('#mpg-output-code');
                outputCode.innerHTML = '';

                var tree = JsonView.createTree(response);
                JsonView.render(tree, outputCode);
                JsonView.expandChildren(tree);

            },
            JSON.stringify(requestBody)
        );

    });

};

// When document is ready:
window.addEventListener('DOMContentLoaded', function(_event) {

    MPG.eventListeners.addDatabases();
    MPG.eventListeners.addCreate();
    MPG.eventListeners.addInsertOne();
    MPG.eventListeners.addCount();
    MPG.eventListeners.addDeleteOne();
    MPG.eventListeners.addFind();

    window.location.hash = '';

});
