import { FormValidator } from './form.validator.js'
import { UsernameManager } from '../../oldJs/username.manager.js'
import { ErrorToast, InfoToast } from '../notifications/toast.js'
import { UsernameDropdown } from '../dropdowns/dropdown.js'
import { StorageManager } from "../storage/storage.manager.js"

export class RegisterFormValidator extends FormValidator {
  constructor(formSelector) {
    super(formSelector)
    this.usernameManager = new UsernameManager()
    this.setupPrependClick()
    this.dropdownList = []
  }

  initializeEventListeners() {
    this.setupNameValidation()
    this.setupEmailValidation()
    this.setupUsernameValidation()
    this.setupPasswordValidation()
    this.setupDivSubmitButton()
    this.setupTermsValidation()
    this.setupFormSubmission()
  }

  setupNameValidation() {
    this.form.find('#real-name').on('input', this.debounce(() => {
      const $field = this.form.find('#real-name')
      const name = $field.val().trim()
      const isValid = /^[\p{L}]+\s[\p{L}]+(?:\s[\p{L}]+)?$/u.test(name)
      this.validateField($field, isValid, isValid ? '' : "Heu d'introduir el vostre nom i cognom(s) separats per espais.")
      $field.data('valid', isValid)
      this.checkFormValidity()
    }, 300)).on('blur', function() {
      const nameParts = $(this).val().trim().split(/\s+/)
      if ([2, 3].includes(nameParts.length)) {
        const secondLastname = nameParts.length === 3 ? ` ${nameParts[2].toUpperCase()}` : ''
        const formattedName = `${nameParts[0].charAt(0).toUpperCase()}${nameParts[0].slice(1).toLowerCase()} ${nameParts[1].toUpperCase()}${secondLastname}`
        $(this).val(formattedName)
      }
    });
  }

  setupEmailValidation() {
    this.form.find('#email').on('input', this.debounce(() => {
      const $field = this.form.find('#email')
      const email = $field.val().trim()
      const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
      this.validateField($field, isValid, isValid ? '' : 'Introduïu una adreça de correu electrònica vàlida')
      $field.data('valid', isValid)
      this.checkFormValidity()
    }, 300)).on('blur', function() {
      $(this).val($(this).val().toLowerCase())
    });
  }

  setupUsernameValidation() {
    const $field = this.form.find('#username')

    $field.on('input', () => {
      let username = $field.val().toLowerCase()

      const isValidFormat = /^[a-z0-9._]{4,12}$/.test(username)

      if (username.length < 4 || username.length > 12) {
        this.validateField($field, false, "La longitud del nom d'usuari ha de ser entre 4 i 12 caràcters")
        $field.data('valid', false)
      } else if (!isValidFormat) {
        this.validateField($field, false, "El nom d'usuari no pot contindre més que minúscules (a-z), nombres(0-9) i els caràcters '.' i '_'")
        $field.data('valid', false)
      } else {
        // Affiche le message "Vérification de la disponibilité..."
        $field.addClass('is-validating')
        this.validateField($field, null, "S'està verificant la disponibilitat...")
        $field.data('valid', null)

        this.checkUsernameAvailability(username)
      }
      this.checkFormValidity()
    });
  }

  checkUsernameAvailability(username) {
    const $field = this.form.find('#username')

    this.usernameManager.checkUsername(username, (result) => {
      // $field.removeClass('is-validating')

      const isValid = result === "El nom d'usuari està disponible"
      this.validateField($field, isValid, isValid ? result : "Este nom d'usuari no està disponible")
      $field.data('valid', isValid)
    });
  }

  setupPrependClick() {
    const inputGroupPrepend = this.form.find('#input-group-prepend')
    const dropdown = new UsernameDropdown()
    inputGroupPrepend.on('click', () => this.handlePrependClick(dropdown))
    
  }
  
  handlePrependClick(dropdown) {
    const nameInput = this.form.find('#real-name')
    const emailInput = this.form.find('#email')
    const usernameInput = this.form.find('#username')
    const name = nameInput ? nameInput.val() : ''
    const email = emailInput ? emailInput.val().split('@')[0] : ''
    const username = usernameInput ? usernameInput.val() : ''
    dropdown.name = name
    dropdown.email = email
    dropdown.username = username

    if (name && email) {
      this.usernameManager.generateUsernames(dropdown, (result) => this.displayUsernameSuggestions(result, dropdown))
    } else {
      const toast = new InfoToast("Per obtenir suggeriments de noms d'usuari heu d'omplir els camps del nom i del correu electrònic.")
      toast.show()
    }
  }

  displayUsernameSuggestions(dropdown) {
    const dropdownOl = this.form.find('#username-dropdown')
    const usernameInput = this.form.find('#username')
    dropdownOl.empty()
  
    dropdown.list.forEach(username => {
      const item = $('<a class="dropdown-item" href="#"></a>').text(username)
      item.on('click', (e) => {
        e.preventDefault()
        usernameInput.val(username)
        dropdownOl.hide()
        this.checkUsernameAvailability(username)
      });
      dropdownOl.append(item)
    });
    dropdownOl.show()
  
    // Gestion du clic en dehors du dropdown
    $(document).on('mousedown.hideDropdown', (event) => {
      if (!$(event.target).closest('#username-dropdown').length) {
        dropdownOl.hide()
        $(document).off('mousedown.hideDropdown')
      }
    });
  }

  setupPasswordValidation() {
    const $passwordField = this.form.find('#pwd-input')
    const $nameField = this.form.find('#real-name')
    const $emailField = this.form.find('#email')
    const $usernameField = this.form.find('#username')

    $passwordField.on('input', this.debounce(() => {
      const password = $passwordField.val()
      const name = $nameField.val().toLowerCase()
      const email = $emailField.val().toLowerCase()
      const username = $usernameField.val().toLowerCase()

      // Séparation du nom et prénom
      const [firstName, lastName] = name.split(/\s+/)

      // Extraction de la partie utilisateur de l'email
      const emailUser = email.split('@')[0]

      // Liste des mots à vérifier
      const wordsToCheck = [firstName, lastName, emailUser, username].filter(Boolean)
      // Vérification du format du mot de passe
      const formatValid = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#~^+/\\´`?'*:%$&()_\-=[\]{}|;"<>.,])[A-Za-z\d!@#~^+/\\´`?'*:%$&()_\-=[\]{}|;"<>.,]{8,25}$/.test(password);

      // Vérification que le mot de passe ne contient pas les informations personnelles
      const containsPersonalInfo = wordsToCheck.some(word => password.toLowerCase().includes(word))
      const isValid = formatValid && !containsPersonalInfo

      let message = ''

      if (!formatValid) {
        message = 'Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial'
      } else if (containsPersonalInfo) {
        message = "Le mot de passe ne doit pas contenir votre nom, prénom, nom d'utilisateur ou partie de votre email"
      }

      this.validateField($passwordField, isValid, message)

      if (!isValid) {
        if (!$('#password-tooltip').length) {
          $passwordField.siblings('.invalid-feedback').append(`
            <span style="display: inline-block;">
              <div id='password-tooltip' data-bs-toggle='tooltip' data-bs-html='true' data-bs-placement='right'
                  title='Le mot de passe doit contenir au moins :
                    - Entre 8 et 25 caractères
                    - Une minuscule
                    - Une majuscule
                    - Un chiffre
                    - Un caractère spécial parmi : !?@#~^+&quot;&apos;*,.;:$&!#$%^&*()_+-=[]{}<>|\\/
                    - Ne pas inclure votre nom, prénom, nom d&apos;utilisateur ou partie de votre email'>
                <i class='bi bi-info-circle'></i>
              </div>
            </span>`)
          new bootstrap.Tooltip($('#password-tooltip'))
        }
        $('#password-tooltip').show()
      } else {
        $('#password-tooltip').hide()
      }

      $passwordField.data('valid', isValid)
      this.checkFormValidity()

    }, 300))
  }

  setupDivSubmitButton() {
    const $submitButtonDiv = $('#submit-button-div')
    const $submitButton = $('#submit-button')

    $submitButtonDiv.on('click', (event) => {
      if ($submitButton.prop('disabled')) {
        event.preventDefault()
        const toast = new ErrorToast("Le formulaire n'est pas encore complet")
        toast.show()
      } 
    });

  }

  setupTermsValidation() {

    this.form.find('#accept-terms').on('change', () => {
      const $field = this.form.find('#accept-terms')
      const isChecked = $field.prop('checked')
      this.validateField($field, isChecked, isChecked ? '' : 'Vous devez accepter les termes et conditions')
      $field.data('valid', isChecked)
      this.checkFormValidity()
    });

  }

  async handleFormSubmission() {

    const formData = {
      name: this.form.find('#real-name').val().trim(),
      email: this.form.find('#email').val().trim(),
      username: this.form.find('#username').val().trim(),
      password: this.form.find('#pwd-input').val(),
      // acceptTerms: this.form.find('#accept-terms').prop('checked')
    }

    const storage = new StorageManager()
    const response = await storage.setServerData('register.form', formData)
    console.log("response", response)
    
  }

}
