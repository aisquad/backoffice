import { BaseFormatter } from "../base.formatter.js"

export class Image extends BaseFormatter {
    constructor(src, options = {}) {
        super(src);
        this.alt = options.alt || 'Product Image';
        this.style = options.style || 'width:50px;height:50px;';
    }

    format() {
        return `<img src="/assets/img/${this.value}" alt="${this.alt}" style="${this.style}">`;
    }

    setAlt(alt) {
        this.alt = alt;
        return this; // Allow chaining
    }

    setStyle(style) {
        this.style = style;
        return this; // Allow chaining
    }
}
