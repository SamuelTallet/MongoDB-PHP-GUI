
/**
 * MongoDB PHP GUI login namespace.
 * 
 * @type {object}
 */
var MPGLogin = {};

/**
 * Event listeners sub-namespace.
 * 
 * @type {object}
 */
MPGLogin.eventListeners = {};

/**
 * Adds an event listener on each "Flip card" button.
 * 
 * @returns {void}
 */
 MPGLogin.eventListeners.addFlipCardButtons = function() {

    document.querySelectorAll('.mpg-flip-card-button').forEach(function(flipCardButton) {

        flipCardButton.addEventListener('click', function(event) {

            event.preventDefault();
            document.querySelector('.flip-card').classList.toggle('flipped');

        });

    });

};

/**
 * Adds an event listener on each required input field.
 * 
 * @returns {void}
 */
MPGLogin.eventListeners.addRequiredInputs = function() {

    document.querySelector('.card').addEventListener('animationend', function(event) {
        event.currentTarget.classList.remove('shake');
    });

    document.querySelectorAll('input[required]').forEach(function(requiredInput) {

        requiredInput.addEventListener('invalid', function(_event) {
            document.querySelector('.card').classList.add('shake');
        });

    });

};

/**
 * Adds an event listener on each form.
 * 
 * @returns {void}
 */
 MPGLogin.eventListeners.addForms = function() {

    document.querySelectorAll('form').forEach(function(form) {

        form.addEventListener('submit', function(event) {

            event.preventDefault();
    
            /**
             * TODO: Submit form if credentials are good.
             * @see https://github.com/SamuelTS/MongoDB-PHP-GUI/issues/21
             */
            event.currentTarget.submit();
        
        });

    });

};

// When document is ready:
window.addEventListener('DOMContentLoaded', function(_event) {

    MPGLogin.eventListeners.addFlipCardButtons();
    MPGLogin.eventListeners.addRequiredInputs();
    MPGLogin.eventListeners.addForms();

});
