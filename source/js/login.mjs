
/**
 * MongoDB PHP GUI login.
 */
class Login {

  constructor() {

    this.background = document.getElementById('mpg-background')
    this.cardsContainer = document.getElementById('mpg-cards')
    this.flipCardButtons = document.querySelectorAll('.mpg-flip-card-button')
    this.requiredInputs = document.querySelectorAll('input[required]')
    this.forms = document.querySelectorAll('form')

  }

  /**
   * Defines background. It's a random abstract image from Unsplash.
   */
  setBackground() {

    const sourceURL = 'https://source.unsplash.com/1920x1080/?abstract'
    const abortController = new AbortController()

    // We will abort fetch request if it takes more than one second.
    const timeoutID = setTimeout(() => abortController.abort(), 1000)

    fetch(sourceURL, { signal: abortController.signal })
      .then(response => {
        clearTimeout(timeoutID)
        return response.blob()
      })
      .then(blob => {
        const blobURL = URL.createObjectURL(blob)
        this.background.style.backgroundImage = `url(${blobURL})`
        this.cardsContainer.classList.add('reveal')
      })
      .catch(_error => {
        console.warn('Failed to fetch unsplash.com. Fallback to local image.')
        this.background.classList.add('fallback')
        this.cardsContainer.classList.add('reveal')
      })

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
         * @see https://github.com/SamuelTS/MongoDB-PHP-GUI/issues/21
         */
        form.submit()
      })
    })

  }

}

(function onDocumentReady() {

  const login = new Login()

  login.setBackground()
  login.listenFlipCardButtons()
  login.listenRequiredInputs()
  login.listenForms()

})()
