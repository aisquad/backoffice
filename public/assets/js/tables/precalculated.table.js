// Factory function to create the table
function createTable(id, data, columns = null, formatters = {}) {
    let sqlData = data;
    let filteredData = [];
    let transformedData = []; // Store transformed data

    // Dispatch (Filter and Transform Data)
    function dispatch() {
        try {
            if (sqlData && sqlData.length > 0) {
                filteredData = sqlData;
                if (columns) {
                    filteredData = sqlData.map(sqlRow => {
                        const filteredItem = {};
                        Object.keys(columns).forEach(key => {
                            const title = columns[key];
                            const value = sqlRow[key];
                            filteredItem[title] = value;
                        });
                        return filteredItem;
                    });
                }
            } else {
                console.warn("No data available.");
                filteredData = [];
            }
        } catch (error) {
            console.error("Error during retrieval or initialization of the data table:", error);
            filteredData = [];
        }
    }

    // Precompute transformations for each column
    function precomputeTransformations() {
        if (!filteredData) return;

        transformedData = filteredData.map(row => {
            const item = {};
            Object.entries(row).forEach(([key, val]) => {
                if (formatters[key]) {
                    let value;
                    if (typeof formatters[key] === 'function') {
                        value = new formatters[key](val);
                    } else if (typeof formatters[key] === 'object' && formatters[key] !== null) {
                        // Check if it's an object with constructor and hrefCallback
                        const { constructor, hrefCallback } = formatters[key];
                        value = new constructor(val, hrefCallback);
                    }
                    item[key] = value.toString();
                } else {
                    item[key] = val;
                }
            });
            return item;
        });
    }

    // Map Data (Transform Data for Table)
    function mapData() {
        return transformedData.map(obj => Object.values(obj));
    }

    // Set Table Head (Create Table Headers)
    function setTableHead(dataHeadings) {
        const $table = $(`${id}`);

        if ($table.length === 0) {
            console.error(`Table with ID ${id} not found.`);
            return;
        }

        const $thead = $('<thead>').prependTo($table);
        const $row = $('<tr>').appendTo($thead);

        // Use the provided column names, or default to data keys
        dataHeadings.forEach(dataHeading => {
            const headingText = columns && columns[dataHeading] ? columns[dataHeading] : dataHeading;
            $('<th>').text(headingText).appendTo($row);
        });
    }

    // Build (Initialize DataTable)
    function build() {
        const $table = $(`${id}`);
        console.info("headings", mapKeys());
        console.info("data", mapData());
        this.dataTable = new simpleDatatables.DataTable($table[0], {
            data: {
                headings: mapKeys(),
                data: mapData()
            },
            searchable: true,
            perPage: 10
        });
    }

    // Initialize Data
    dispatch();
    precomputeTransformations(); // Precompute transformations

    // Public API
    return {
        build: build,
        mapKeys: mapKeys,
        mapData: mapData,
        setTableHead: setTableHead
    };
}
