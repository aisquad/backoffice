import { BaseFormatter } from "../base.formatter.js"

export class Monetary extends BaseFormatter{
    value = 0

    constructor(value){
        super(value)
        this.value = parseFloat(value).toFixed(2)
    }

    toString() {
        return `${this.value} €`
    }
}