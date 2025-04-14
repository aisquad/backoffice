export class PasswordToggle {
    constructor(inputId = 'password', toggleId = 'toggle-password') {
      this.inputId = inputId;
      this.toggleId = toggleId;
      this.isClicked = false;
      this.init();
    }
  
    init() {
      this.loadJQuery(() => {
        this.setupEventListeners();
      });
    }
  
    loadJQuery(callback) {
      if (typeof jQuery === 'undefined') {
        const script = document.createElement('script');
        script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js';
        script.integrity = 'sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo='
        script.crossOrigin = 'anonymous'
        script.onload = callback;
        document.head.appendChild(script);
        console.log("jQuery loaded")
      } else {
        callback();
      }
    }
  
    setupEventListeners() {
      const $togglePassword = $(`#${this.toggleId}`);
      const $passwordInput = $(`#${this.inputId}`);
      const $toggleIcon = $togglePassword.find('i');
  
      $togglePassword.on({
        mouseenter: () => {
          if (!this.isClicked) this.showPassword($passwordInput, $toggleIcon);
        },
        mouseleave: () => {
          if (!this.isClicked) this.hidePassword($passwordInput, $toggleIcon);
        },
        click: () => {
          this.isClicked = !this.isClicked;
          if (this.isClicked) {
            this.showPassword($passwordInput, $toggleIcon);
          } else {
            this.hidePassword($passwordInput, $toggleIcon);
          }
        }
      });
    }
  
    showPassword($input, $icon) {
      $input.attr('type', 'text');
      $icon.removeClass('bi-eye').addClass('bi-eye-slash');
    }
  
    hidePassword($input, $icon) {
      $input.attr('type', 'password');
      $icon.removeClass('bi-eye-slash').addClass('bi-eye');
    }
  }
  