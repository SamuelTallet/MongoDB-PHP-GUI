
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
 * Field names of current collection.
 * 
 * @type {Array}
 */
MPG.collectionFields = [];

/**
 * Indexes of current collection.
 * 
 * @type {Array}
 */
MPG.collectionIndexes = [];

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
 * Reloads collections of a specific database.
 * 
 * @param {string} databaseName
 * 
 * @returns {void}
 */
MPG.reloadCollections = function(databaseName) {

    MPG.helpers.doAjaxRequest(
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
 * Reloads fields of current collection.
 * 
 * @returns {void}
 */
MPG.reloadCollectionFields = function() {

    MPG.helpers.doAjaxRequest(
        'GET',
        '/ajax/database/' + MPG.databaseName + '/collection/'
            + MPG.collectionName + '/enumFields',
        function(response) {

            JSON.parse(response).forEach(function(collectionField) {
                if ( typeof collectionField === 'string' ) {
                    MPG.collectionFields.push(collectionField);
                }
            });

            var indexableFieldsList = document.querySelector('#mpg-indexable-fields-list');
            indexableFieldsList.innerHTML = '';
        
            MPG.collectionFields.forEach(function(collectionField) {
        
                indexableFieldsList.innerHTML += '<li><input type="checkbox"'
                    + ' class="mpg-collection-field-checkbox"'
                        + (( collectionField === '_id' ) ? ' disabled' : '')
                            + ' value="' + collectionField + '"> ' + collectionField + ' </li>';
        
            });

        },
        null
    );

};

/**
 * Reloads indexes of current collection.
 * 
 * @returns {void}
 */
MPG.reloadCollectionIndexes = function() {

    MPG.helpers.doAjaxRequest(
        'GET',
        '/ajax/database/' + MPG.databaseName + '/collection/'
            + MPG.collectionName + '/listIndexes',
        function(response) {

            MPG.collectionIndexes = JSON.parse(response);
            
            document.querySelector('#mpg-indexes-column').classList.remove('d-none');

            var indexesTableBody = document.querySelector('#mpg-indexes-table tbody');
            indexesTableBody.innerHTML = '';
            
            MPG.collectionIndexes.forEach(function(collectionIndex) {
                
                var collectionIndexKeysHtml = '';
                
                for (var collectionIndexKey in collectionIndex.keys) {
        
                    if ( !collectionIndex.keys.hasOwnProperty(collectionIndexKey) ) {
                        continue;
                    }
        
                    var collectionIndexOrder = ' (ASC) ';
        
                    if ( collectionIndex.keys[collectionIndexKey] === -1 ) {
                        collectionIndexOrder = ' (DESC) ';
                    }
        
                    collectionIndexKeysHtml += collectionIndexKey + collectionIndexOrder;
        
                }
                
                var collectionIndexDropButton = '<button'
                    + ' data-index-name="' + collectionIndex.name + '"'
                        + ' class="mpg-index-drop-button btn btn-danger">'
                            + 'Drop index</button>';
                
                indexesTableBody.innerHTML += '<tr><td>' + collectionIndex.name + '</td>'
                    + '<td>' + collectionIndexKeysHtml + '</td>'
                        + '<td>' + collectionIndexDropButton + '</td></tr>';
                
                MPG.eventListeners.addDropIndex();
        
            });

        },
        null
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

            document.querySelector('#mpg-indexable-fields-list').innerHTML =
                '<li><i>Please select a collection.</i></li>';

            document.querySelector('#mpg-indexes-column').classList.add('d-none');

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
            MPG.collectionFields = [];

            MPG.reloadCollectionFields();
            MPG.reloadCollectionIndexes();

        });

    });

};

/**
 * Adds an event listener on "Create index" button.
 * 
 * @returns {void}
 */
MPG.eventListeners.addCreateIndex = function() {

    document.querySelector('#mpg-create-index-button')
        .addEventListener('click', function(_event) {

        if ( MPG.databaseName === '' || MPG.collectionName === '' ) {
            return window.alert('Please select a database and a collection.');
        }

        var indexKeys = document.querySelectorAll('.mpg-collection-field-checkbox:checked');

        if ( indexKeys.length === 0 ) {
            return window.alert('Please select one or many fields.');
        }

        // TODO: Manage index order by field.
        var indexOrder = parseInt(document.querySelector('#mpg-index-order-select').value);
        var uniqueIndex = document.querySelector('#mpg-unique-index-select').value;
        var indexIsUnique = ( uniqueIndex === 'true' ) ? true : false;
        
        var requestBody = {};
        requestBody.key = {};
        requestBody.options = { "unique" : indexIsUnique };

        indexKeys.forEach(function(indexKey) {
            requestBody.key[indexKey.value] = indexOrder;
        });

        MPG.helpers.doAjaxRequest(
            'POST',
            '/ajax/database/' + MPG.databaseName + '/collection/'
                + MPG.collectionName + '/createIndex',
            function(response) {

                var createdIndexName = JSON.parse(response);
                window.alert('Index created with name: ' + createdIndexName);

                MPG.reloadCollectionIndexes();

            },
            JSON.stringify(requestBody)
        );

    });

};

/**
 * Adds an event listener on "Drop index" buttons.
 * 
 * @returns {void}
 */
MPG.eventListeners.addDropIndex = function() {

    var indexDropButtons = document.querySelectorAll('.mpg-index-drop-button');

    indexDropButtons.forEach(function(indexDropButton) {

        indexDropButton.addEventListener('click', function(_event) {

            var requestBody = { "indexName": indexDropButton.dataset.indexName };

            MPG.helpers.doAjaxRequest(
                'POST',
                '/ajax/database/' + MPG.databaseName + '/collection/'
                    + MPG.collectionName + '/dropIndex',
                function(response) {
    
                    if ( JSON.parse(response) === true ) {
                        MPG.reloadCollectionIndexes();
                    }
    
                },
                JSON.stringify(requestBody)
            );

        });

    });

};

// When document is ready:
window.addEventListener('DOMContentLoaded', function(_event) {

    MPG.eventListeners.addDatabases();
    MPG.eventListeners.addCreateIndex();

});