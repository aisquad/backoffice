import { BaseFormatter } from "../base.formatter.js"

export class Anchor extends BaseFormatter {
    constructor(text, options = {}) {
        super(text);
        this.href = options.href || '#';
        this.className = options.className || 'text-primary fw-bold';
    }

    format() {
        return `<a href="${this.href}" class="${this.className}">${this.value}</a>`;
    }

    setHref(href) {
        this.href = href;
        return this; // Allow chaining
    }

    setClass(className) {
        this.className = className;
        return this; // Allow chaining
    }
}
