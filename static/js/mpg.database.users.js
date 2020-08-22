
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
 * Reloads users of a specific database.
 * 
 * @param {string} databaseName
 * 
 * @returns {void}
 */
MPG.reloadUsers = function(databaseName) {

    var requestBody = { 'databaseName': databaseName };

    MPG.helpers.doAjaxRequest(
        'POST', MPG_BASE_URL + '/ajaxDatabaseListUsers', function(response) {

            var usersInfo = JSON.parse(response);

            document.querySelector('#mpg-users-table').classList.add('d-none');

            var usersTableBody = document.querySelector('#mpg-users-table tbody');
            usersTableBody.innerHTML = '';

            if ( usersInfo.users.length === 0 ) {
                return;
            }

            usersInfo.users.forEach(function(userInfo) {

                var userRoles = [];

                userInfo.roles.forEach(function(userRole) {
                    userRoles.push(userRole.role + ' (' + userRole.db + ')');
                });

                var userDropButton = '<button'
                + ' data-user-name="' + userInfo.user + '"'
                    + ' class="mpg-drop-user-button btn btn-danger">'
                        + 'Drop user</button>';

                usersTableBody.innerHTML += '<tr><td>' + userInfo.user + '</td>'
                    + '<td>' + userRoles.join(', ') + '</td>'
                        + '<td>' + userDropButton + '</td></tr>';

            });

            MPG.eventListeners.addDropUser();

            document.querySelector('#mpg-users-table').classList.remove('d-none');

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

            document.querySelector('#mpg-please-select-a-db').classList.add('d-none');
            document.querySelector('#mpg-open-create-user-modal-button').classList.remove('d-none');

            MPG.reloadUsers(databaseLink.dataset.databaseName);

        });

    });

};

/**
 * Adds an event listener on buttons that open modals.
 * 
 * @returns {void}
 */
MPG.eventListeners.addModalOpen = function() {

    document.querySelectorAll('.btn[data-open="modal"]')
        .forEach(function(modalOpenButton) {

        modalOpenButton.addEventListener('click', function(event) {

            var modal = document.getElementById(event.currentTarget.dataset.modalId);
            modal.classList.add('d-block');

        });
        
    });

};

/**
 * Adds an event listener on "Create user" button.
 * 
 * @returns {void}
 */
MPG.eventListeners.addCreateUser = function() {

    document.querySelector('#mpg-create-user-button').addEventListener('click', function(_event) {

        if ( MPG.databaseName === '' ) {
            return window.alert('Please select a database.');
        }
        
        var userName = document.querySelector('#mpg-user-name').value;

        if ( userName.trim() === '' ) {
            return window.alert('Please enter an user name.');
        }

        var userPassword = document.querySelector('#mpg-user-password').value;

        if ( userPassword.trim() === '' ) {
            return window.alert('Please enter an user password.');
        }

        // TODO: Manage several roles per user.
        var userRole = document.querySelector('#mpg-user-role').value;
        var userRoleDatabase = document.querySelector('#mpg-user-role-database').value;

        var requestBody = {
            'databaseName': MPG.databaseName,
            'userName': userName,
            'userPassword': userPassword,
            'userRoles': [
                { 'role': userRole, 'db': userRoleDatabase }
            ]
        };

        MPG.helpers.doAjaxRequest(
            'POST',
            MPG_BASE_URL + '/ajaxDatabaseCreateUser',
            function(response) {

                if ( JSON.parse(response) === true ) {

                    MPG.reloadUsers(MPG.databaseName);
                    document.querySelector('#mpg-create-user-modal').classList.remove('d-block');

                }

            },
            JSON.stringify(requestBody)
        );

    });

};

/**
 * Adds an event listener on "Drop user" buttons.
 * 
 * @returns {void}
 */
MPG.eventListeners.addDropUser = function() {

    var userDropButtons = document.querySelectorAll('.mpg-drop-user-button');

    userDropButtons.forEach(function(userDropButton) {

        userDropButton.addEventListener('click', function(_event) {

            var dropConfirmation = window.confirm(
                'Do you REALLY want to DROP user: ' + userDropButton.dataset.userName
            );
    
            if ( dropConfirmation === false ) {
                return;
            }

            var requestBody = {
                'databaseName': MPG.databaseName,
                'userName': userDropButton.dataset.userName
            };

            MPG.helpers.doAjaxRequest(
                'POST',
                MPG_BASE_URL + '/ajaxDatabaseDropUser',
                function(response) {
    
                    if ( JSON.parse(response) === true ) {
                        MPG.reloadUsers(MPG.databaseName);
                    }
    
                },
                JSON.stringify(requestBody)
            );

        });

    });

};

/**
 * Adds an event listener on buttons that close modals.
 * 
 * @returns {void}
 */
MPG.eventListeners.addModalClose = function() {

    document.querySelectorAll('.btn[data-dismiss="modal"]')
        .forEach(function(modalCloseButton) {

        modalCloseButton.addEventListener('click', function(event) {

            var modal = document.getElementById(event.currentTarget.dataset.modalId);
            modal.classList.remove('d-block');

        });
        
    });

};

// When document is ready:
window.addEventListener('DOMContentLoaded', function(_event) {

    MPG.eventListeners.addMenuToggle();
    MPG.eventListeners.addDatabases();
    MPG.eventListeners.addModalOpen();
    MPG.eventListeners.addCreateUser();
    MPG.eventListeners.addModalClose();

});
