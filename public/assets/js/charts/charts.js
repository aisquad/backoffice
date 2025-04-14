export class ChartManager {
    type = null
    id = null
    data = {}
    options = {}
    container = null
    chart = null

    constructor(id, data) {
        this.checkLibraries()
        this.id = id
        this.data = data
    }

    checkLibraries() {
        if (this.type === 'echart' && typeof echarts === 'undefined') {
            console.error('ECharts library not loaded');
        }
        else if (this.type === 'apex' && typeof ApexCharts === 'undefined') {
            console.error('Apex library not loaded');
        } 
    }

    init() {
        try {
            this.mapOptions()
            this.renderChart()
        } catch (error) {
            console.error("Error during chart initialization:", error);
        }
    }

    mapOptions() {
        if (!this.data || this.data.length === 0) {
            console.error('No data received from the service');
            return;
        }
    }

    renderChart() {
        this.container = $(this.id)[0];
        if (!this.container) {
            console.error(`Container with ID ${this.id} not found`);
            return;
        }
    }

    update(newData) {
        this.data = newData;
        this.mapOptions();
        this.updateChart();
    }

    updateChart() {
        console.warn('updateChart() method should be implemented in derived class');
    }
}

class ReportApexChart extends ChartManager {
    type = 'apex'

    constructor(id, data) {
        super(id, data)
        this.options = {
            series: [
                { name: 'Sales', data: [] },
                { name: 'Revenue', data: [] },
                { name: 'Customers', data: [] }
            ],
            chart: {
                height: 350,
                type: 'area',
                toolbar: { show: false }
            },
            markers: { 
                size: 4,
                hover: {
                    sizeOffset: 2
                }
            },
            colors: ['#4154f1', '#2eca6a', '#ff771d'],
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.3,
                    opacityTo: 0.4,
                    stops: [0, 90, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: { type: 'datetime', categories: [] },
            tooltip: { x: { format: 'dd/MM/yy HH:mm' } }
        }
    }

    mapOptions() {
        super.mapOptions()
        this.options.xaxis.categories = this.data.map(item => item.timestamp)
        this.options.series[0].data = this.data.map(item => item.sales)
        this.options.series[1].data = this.data.map(item => item.revenue)
        this.options.series[2].data = this.data.map(item => item.customers)
    }

    renderChart() {
        super.renderChart()
        this.chart = new ApexCharts(this.container, this.options)
        this.chart.render()
    }

    updateChart() {
        if (this.chart) {
            this.chart.updateOptions(this.options);
            this.chart.updateSeries(this.options.series);
        }
    }
}

class TrafficEChart extends ChartManager {
    type = 'echart'
    constructor(chartId, data) {
        super(chartId, data);
        this.options = {
            tooltip: {
                trigger: 'item'
            },
            legend: {
                top: '5%',
                left: 'center'
            },
            series: [{
                name: 'Access From',
                type: 'pie',
                radius: ['40%', '70%'],
                avoidLabelOverlap: false,
                label: {
                    show: false,
                    position: 'center'
                },
                emphasis: {
                    label: {
                        show: true,
                        fontSize: '18',
                        fontWeight: 'bold'
                    }
                },
                labelLine: {
                    show: false
                },
                data: []
            }]
        }
    }

    mapOptions() {
        super.mapOptions()
        this.options.series[0].data = this.data.map(item => ({
            value: item.value,
            name: item.source_name
        }))
    }

    renderChart() {
        super.renderChart()
        this.chart = echarts.init(this.container);
        this.chart.setOption(this.options);
    }

    updateChart() {
        if (this.chart) {
            this.chart.setOption(this.options);
        }
    }
}


class BudgetEChart extends ChartManager {
    type = 'echart'
    constructor(id, data) {
        super(id, data)
        this.options = {
            legend: {
                data: ['Allocated Budget', 'Actual Spending']
            },
            radar: {
                indicator: []
            },
            series: [{
                name: 'Budget vs spending',
                type: 'radar',
                data: [
                    { name: 'Allocated Budget', value: [] },
                    { name: 'Actual Spending', value: [] }
                ]
            }]
        }
    }

    mapOptions() {
        super.mapOptions()
        this.options.radar.indicator = this.data.map(item => ({
            name: item.category,
            max: item.max_value
        }));
        this.options.series[0].data[0].value = this.data.map(item => item.allocated_budget)
        this.options.series[0].data[1].value = this.data.map(item => item.actual_spending)
    }

    renderChart() {
        super.renderChart()
        this.chart = echarts.init(this.container)
        this.chart.setOption(this.options)
    }

    updateChart() {
        if (this.chart) {
            this.chart.setOption(this.options);
        }
    }
}

export class ChartFactory {
    static createChart(id, data) {
        if (id === '#reports-chart') {
            return new ReportApexChart(id, data);
        } else if (id === '#budget-chart') {
            return new BudgetEChart(id, data);
        } else if (id === '#traffic-chart') {
            return new TrafficEChart(id, data);
        } else {
            throw new Error(`No chart class found for ID: ${id}`);
        }
    }
}
