export class FormValidator {
    constructor(formSelector) {
      this.form = $(formSelector);
      this.formValid = false;
      this.initializeEventListeners();
    }
  
    initializeEventListeners() {
      // Méthode à surcharger dans les classes enfants
    }
  
    checkFormValidity() {
      this.formValid = true;
      this.form.find('input, select, textarea').each((index, element) => {
        const $element = $(element);
        if ($element.prop('required') || $element.data('valid') !== undefined) {
          const isValid = $element.prop('required') ? $element.is(':valid') && $element.data('valid') !== false : $element.data('valid');
          this.formValid = this.formValid && isValid;
        }
      });
      this.form.find('button[type="submit"]').prop('disabled', !this.formValid);
    }
  
    debounce(func, delay) {
      let timeoutId;
      return (...args) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => func.apply(this, args), delay);
      };
    }

    validateField($field, isValid, message) {
      $field.removeClass('is-valid is-invalid is-validating'); 
      const $feedback = $field.siblings('.invalid-feedback');
    
      if (message === "Vérification de la disponibilité...") {
        $field.addClass('is-validating'); 
        if ($feedback.length) {
          $feedback.text(message);
        } else {
          $field.after(`<div class="invalid-feedback is-validating">${message}</div>`);
        }
      } else {
        $field.addClass(isValid === null ? '' : (isValid ? 'is-valid' : 'is-invalid'));
        if ($feedback.length) {
          $feedback.text(message);
          $feedback.removeClass('is-validating');
        } else {
          $field.after(`<div class="invalid-feedback ${isValid ? 'valid-feedback' : ''}">${message}</div>`);
        }
      }
      $field.data('valid', isValid);
    }

    setupFormSubmission() {
      this.form.on('submit', (e) => {
        e.preventDefault();
        if (this.formValid) {
          this.handleFormSubmission();
        }
      });
    }
  
    handleFormSubmission() {
      // Méthode à surcharger dans les classes enfants
    }
  }