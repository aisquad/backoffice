import { BaseFormatter } from "../base.formatter.js"

export class Span extends BaseFormatter {
    badgeClasses = {
        'Approved': 'bg-success',
        'Pending': 'bg-warning',
        'Rejected': 'bg-danger'
    }

    translations = {
        'Approved': 'aprovada',
        'Pending': 'pendent',
        'Rejected': 'rebujada'
    }

    constructor(value, options = {}) {
        super(value);
        this.className = options.className || '';
    }

    getBadgeClasses() {
        const badgeClass = this.badgeClasses[this.value] || 'bg-secondary'
        if (badgeClass) {
            this.class += ` ${badgeClass}`
        }
    }

    format() {
        this.getBadgeClasses()
        const tr = this.translations[this.status] || this.status
        return `<span class="${this.className}">${this.value}</span>`;
    }

    setClass(className) {
        this.className = className;
        return this; // Allow chaining
    }
}