
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
 * Adds an event listener on "Login" button.
 * 
 * @returns {void}
 */
MPG.eventListeners.addLogin = function() {

    document.querySelector('.card').addEventListener('animationend', function(event) {

        event.currentTarget.classList.remove('apply-shake');

    });

    document.querySelector('#mpg-login-button').addEventListener('click', function(_event) {

        if ( document.querySelector('input[name="host"]').value === '' 
            || document.querySelector('input[name="port"]').value === '' )
        {
            document.querySelector('.card').classList.add('apply-shake');
        }

    });

};

// When document is ready:
window.addEventListener('DOMContentLoaded', function(_event) {

    MPG.eventListeners.addLogin();

});
