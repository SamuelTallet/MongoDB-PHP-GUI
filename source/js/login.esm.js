/**
 * MongoDB PHP GUI login.
 */
class Login {

  constructor() {

    this.cardsContainer = document.getElementById('mpg-cards')
    this.flipCardButtons = document.querySelectorAll('.mpg-flip-card-button')
    this.requiredInputs = document.querySelectorAll('input[required]')
    this.forms = document.querySelectorAll('form')

  }

  /**
   * Adds an event listener on each "Flip card" button.
   */
  listenFlipCardButtons() {

    this.flipCardButtons.forEach(flipCardButton => {
      flipCardButton.addEventListener('click', event => {
        event.preventDefault()
        this.cardsContainer.classList.toggle('flipped')
      })
    })

  }

  /**
   * Adds an event listener on each required input field.
   */
  listenRequiredInputs() {

    this.cardsContainer.addEventListener('animationend', _event => {
      this.cardsContainer.classList.remove('shake')
    })

    this.requiredInputs.forEach(requiredInput => {
      requiredInput.addEventListener('invalid', _event => {
        this.cardsContainer.classList.add('shake')
      })
    })

  }

  /**
   * Adds an event listener on each form.
   */
  listenForms() {

    this.forms.forEach(form => {
      form.addEventListener('submit', event => {
        event.preventDefault()

        /**
         * TODO: Submit form if credentials are good.
         *
         * @see https://github.com/SamuelTallet/MongoDB-PHP-GUI/issues/21
         */
        form.submit()
      })
    })

  }

}

(function onDocumentReady() {

  const login = new Login()

  login.listenFlipCardButtons()
  login.listenRequiredInputs()
  login.listenForms()

})()
