export class Table {
    id = ''
    sqlData = []
    columns = {}
    headings = {}
    formatters = {}
    data = {} // Added: Store filtered data

    constructor(id, data, columns = null, formatters = {}) {
        this.id = id
        this.sqlData = data
        this.columns = columns
        this.formatters = formatters
        this.dispatch()
    }

    dispatch() {
        try {
            if (this.sqlData && this.sqlData.length > 0) {
                // Filter data based on excluded columns
                this.data = this.sqlData
                if (this.columns) {

                    this.data = this.sqlData.map(sqlRow => {
                        const filteredItem = {};
                        Object.keys(this.columns).forEach(key => {
                            const title = this.columns[key]
                            const value = sqlRow[key]
                            filteredItem[title] = value
                        });
                        return filteredItem;
                    });
                }
            } else {
                console.warn("No data available.");
            }
        } catch (error) {
            console.error("Error during retrieval or initialization of the data table:", error);
        }
    }

    build() {
        const $table = $(this.id)
        this.dataTable = new simpleDatatables.DataTable($table[0], {
            data: {
                headings: this.mapKeys(),
                data: this.mapData()
            },
            searchable: true,
            perPage: 10
        });
    }

    mapKeys() {
        if (!this.data || this.data.length === 0) {
            return [];
        }
        return Object.keys(this.data[0])
    }

    mapData() {
        const data = this.data.map(row => {
            const item = {};
            Object.entries(row).forEach(([key, val]) => {
                if (this.formatters[key]) {
                    let formatterConfig = this.formatters[key];
                    let formattedValue;

                    const formatterType = typeof formatterConfig;
    
                    if (formatterType === 'function') {
                        // Use the function as a direct formatter

                        try {
                            formattedValue = new formatterConfig(val);
                        } catch (e) {
                            formattedValue = formatterConfig(val);
                        }
                    } else if (formatterType === 'object' && formatterConfig !== null && formatterConfig.constructor) {
                        // Use the constructor with potential attribute callbacks
                        let formatter = new formatterConfig.constructor(val);
    
                        // Apply attribute callbacks
                        for (const attribute in formatterConfig) {
                            if (attribute !== 'constructor' && typeof formatterConfig[attribute] === 'function' && typeof formatter[attribute] === 'function') {
                                formatter[attribute](formatterConfig[attribute](val));
                            }
                        }
                        formattedValue = formatter.toString();
                    } else {
                        formattedValue = val; // Default: use value as is
                    }
    
                    item[key] = formattedValue;
                } else {
                    item[key] = val;
                }
            });
            return item;
        });
        return data.map(obj => Object.values(obj));
    }
    
    setTableHead(dataHeadings) {
        const $table = $(`${this.id}`)

        if ($table.length === 0) {
            return
        }

        const $thead = $('<thead>').prependTo($table)
        const $row = $('<tr>').appendTo($thead)

        // Use the provided column names, or default to data keys
        dataHeadings.forEach(dataHeading => {
            const headingText = this.columns && this.columns[dataHeading] ? this.columns[dataHeading] : dataHeading;
            $('<th>').text(headingText).appendTo($row)
        })
    }
}
