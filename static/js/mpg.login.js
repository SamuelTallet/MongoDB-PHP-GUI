
/**
 * MongoDB PHP GUI namespace.
 * 
 * @type {object}
 */
var MPG = {};

/**
 * Event listeners sub-namespace.
 * 
 * @type {object}
 */
MPG.eventListeners = {};

/**
 * Adds an event listener on "URI" field.
 * 
 * @returns {void}
 */
MPG.eventListeners.addUriField = function () {

    document.getElementById('mpg-uri-field').addEventListener('input', function(event) {

        var uri = event.currentTarget.value;

        if ( /^mongodb:\/\/.+/.test(uri) ) {

            // This hack forces URI to be well parsed.
            uri = uri.replace('mongodb://', 'http://');

            var url = new URL(uri);

            // Special characters in "user" field and "password" field will be re-encoded on back-end.
            document.querySelector('input[name="user"]').value = decodeURIComponent(url.username);
            document.querySelector('input[name="password"]').value = decodeURIComponent(url.password);

            document.querySelector('input[name="host"]').value = url.hostname;
            document.querySelector('input[name="port"]').value = url.port;
            document.querySelector('input[name="database"]').value = url.pathname.replace('/', '');

        }

    });

};

/**
 * Adds an event listener on "Login" button.
 * 
 * @returns {void}
 */
MPG.eventListeners.addLoginButton = function() {

    document.querySelector('.card').addEventListener('animationend', function(event) {

        event.currentTarget.classList.remove('apply-shake');

    });

    document.getElementById('mpg-login-button').addEventListener('click', function(_event) {

        if ( document.querySelector('input[name="host"]').value === '' ) {

            document.querySelector('.card').classList.add('apply-shake');
            
        }

    });

};

// When document is ready:
window.addEventListener('DOMContentLoaded', function(_event) {

    MPG.eventListeners.addUriField();
    MPG.eventListeners.addLoginButton();

});
