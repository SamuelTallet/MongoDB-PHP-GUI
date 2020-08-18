
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

    var requestBody = { 'databaseName': databaseName };

    MPG.helpers.doAjaxRequest(
        'POST', MPG_BASE_URL + '/ajaxDatabaseListCollections', function(response) {

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

        },
        JSON.stringify(requestBody)
    );

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
 * Adds an event listener on each database.
 * 
 * @returns {void}
 */
MPG.eventListeners.addDatabases = function() {

    document.querySelectorAll('.mpg-database-link').forEach(function(databaseLink) {

        databaseLink.addEventListener('click', function(_event) {
            
            MPG.databaseName = databaseLink.dataset.databaseName;

            document.querySelectorAll('.mpg-database-link').forEach(function(databaseLink) {
                databaseLink.classList.remove('font-weight-bold');
            });

            databaseLink.classList.add('font-weight-bold');

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

            document.querySelectorAll('.mpg-collection-link').forEach(function(collectionLink) {
                collectionLink.classList.remove('font-weight-bold');
            });

            collectionLink.classList.add('font-weight-bold');

        });

    });

};

/**
 * Adds an event listener on "Create coll." button.
 * 
 * @returns {void}
 */
MPG.eventListeners.addCreateColl = function() {

    document.querySelector('#mpg-create-coll-button').addEventListener('click', function(_event) {

        var databaseName = window.prompt('Database name to create or use');
        if ( databaseName === null ) {
            return;
        }
        
        var collectionName = window.prompt('Collection name to create');
        if ( collectionName === null ) {
            return;
        }
    
        var requestBody = {
            'databaseName': databaseName,
            'collectionName': collectionName
        };
    
        MPG.helpers.doAjaxRequest(
            'POST',
            MPG_BASE_URL + '/ajaxDatabaseCreateCollection',
            function(response) {

                if ( JSON.parse(response) === true ) {
                    window.location.hash = '#';
                    window.location.reload();
                }

            },
            JSON.stringify(requestBody)
        );

    });

};

/**
 * Adds an event listener on "Rename coll." button.
 * 
 * @returns {void}
 */
MPG.eventListeners.addRenameColl = function() {

    document.querySelector('#mpg-rename-coll-button').addEventListener('click', function(_event) {

        if ( MPG.databaseName === '' || MPG.collectionName === '' ) {
            return window.alert('Please select a database and a collection.');
        }

        var newCollectionName = window.prompt('New collection name');
        if ( newCollectionName === null ) {
            return;
        }

        var requestBody = {
            'databaseName': MPG.databaseName,
            'oldCollectionName': MPG.collectionName,
            'newCollectionName': newCollectionName
        };

        MPG.helpers.doAjaxRequest(
            'POST',
            MPG_BASE_URL + '/ajaxCollectionRename',
            function(response) {

                if ( JSON.parse(response) === true ) {
                    window.location.hash = '#' + MPG.databaseName;
                    MPG.reloadCollections(MPG.databaseName);
                }

            },
            JSON.stringify(requestBody)
        );

    });

};

/**
 * Adds an event listener on "Drop coll." button.
 * 
 * @returns {void}
 */
MPG.eventListeners.addDropColl = function() {

    document.querySelector('#mpg-drop-coll-button').addEventListener('click', function(_event) {

        if ( MPG.databaseName === '' || MPG.collectionName === '' ) {
            return window.alert('Please select a database and a collection.');
        }

        var dropConfirmation = window.confirm(
            'Do you REALLY want to DROP collection: '
                + MPG.databaseName + '.' + MPG.collectionName
        )

        if ( dropConfirmation === false ) {
            return;
        }

        var requestBody = {
            'databaseName': MPG.databaseName,
            'collectionName': MPG.collectionName
        };

        MPG.helpers.doAjaxRequest(
            'POST',
            MPG_BASE_URL + '/ajaxCollectionDrop',
            function(response) {

                if ( JSON.parse(response) === true ) {
                    window.location.hash = '#';
                    window.location.reload();
                }

            },
            JSON.stringify(requestBody)
        );

    });

};

// When document is ready:
window.addEventListener('DOMContentLoaded', function(_event) {

    MPG.eventListeners.addMenuToggle();
    MPG.eventListeners.addDatabases();
    MPG.eventListeners.addCreateColl();
    MPG.eventListeners.addRenameColl();
    MPG.eventListeners.addDropColl();

});
