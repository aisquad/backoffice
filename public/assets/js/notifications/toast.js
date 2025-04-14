export class Toast {
    /**
     * Header background color
     * Possible values: 'primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'
     * 
     * Header text color
     * Possible values: 'white', 'black', 'muted'
     * 
     * Body background color
     * Possible values: 'primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'
     * 
     * Body text color
     * Possible values: 'primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'
     * 
     * Toast position
     * Possible values:
     * 'top-0 start-0', 'top-0 start-50 translate-middle-x', 'top-0 end-0',
     * 'top-50 start-0 translate-middle-y', 'top-50 start-50 translate-middle', 'top-50 end-0 translate-middle-y',
     * 'bottom-0 start-0', 'bottom-0 start-50 translate-middle-x', 'bottom-0 end-0'
     */
  
  constructor(title, message, options = {}) {
    this.title = title;
    this.message = message;
    this.options = {
      ...options,
      backgroundColor: options.backgroundColor || 'white',
      headerBackgroundColor: options.headerBackgroundColor || 'primary',
      headerTextColor: options.headerTextColor || 'white',
      bodyBackgroundColor: options.bodyBackgroundColor || 'white',
      bodyTextColor: options.bodyTextColor || 'dark',
      duration: (options.duration || Math.ceil(message.length / 7) / 2 + 1) * 1000,
      position: options.position || 'top-0 start-50 translate-middle-x',
      };
    this.toast = null;
  }

  create() {
    const toastElement = document.createElement('div');
    toastElement.className = `toast custom-toast ${this.options.position}`;
    toastElement.setAttribute('role', 'alert');
    toastElement.setAttribute('aria-live', 'assertive');
    toastElement.setAttribute('aria-atomic', 'true');
    toastElement.style.backgroundColor = this.options.backgroundColor;
    toastElement.style.position = 'fixed';
    toastElement.style.zIndex = '1060'

    toastElement.innerHTML = `
    <div class="toast-header bg-${this.options.headerBackgroundColor} text-${this.options.headerTextColor}">
      <strong class="me-auto">${this.title}</strong>
      <button type="button" class="btn-close ${this.options.headerBackgroundColor !== 'white' ? 'btn-close-white' : ''}" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body bg-${this.options.bodyBackgroundColor} text-${this.options.bodyTextColor}">
      ${this.message}
    </div>
  `;
    document.body.appendChild(toastElement);
    this.toast = new bootstrap.Toast(toastElement, { autohide: true, delay: this.options.duration });
  }

  show() {
    if (!this.toast) {
      this.create();
    }
    this.toast.show()
  }
}

export class ErrorToast extends Toast {
  constructor(message, options = {}) {
    const errorOptions = {
      ...options,
      headerBackgroundColor: 'danger'
    };
    super("Error", message, errorOptions);
  }
}

export class WarningToast extends Toast {
  constructor(message, options = {}) {
    const errorOptions = {
      ...options,
      headerBackgroundColor: 'warning'
    };
    super("Avís", message, errorOptions);
  }
}

export class InfoToast extends Toast {
  constructor(message, options = {}) {
    const errorOptions = {
      ...options,
      headerBackgroundColor: 'info'
    };
    super("Notificació", message, errorOptions);
  }
}


export class SuccessToast extends Toast {
  constructor(title, message, options = {}) {
    const errorOptions = {
      ...options,
      headerBackgroundColor: 'success',
      position: 'top-0 end-0 p-3'
    };
    super(title, message, errorOptions);
  }
}
