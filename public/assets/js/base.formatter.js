export class BaseFormatter {
    constructor(value) {
        this.value = value;
    }

    format() {
        return this.value; // Default implementation: return the value as is
    }

    toString() {
        return this.format();
    }
}
