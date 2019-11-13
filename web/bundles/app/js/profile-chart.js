class Controller {
    constructor(container) {
        this.chart_identifier = container;
        this.group = 0;
        this.chart = 1;
        this.types = {
            d: 'Days',
            m: 'Months',
        };
        this.type = 'd';
        this.init();
    };

    init() {

        var data = JSON.parse(datas.split('&quot;').join('"'));
        if(data) {
            console.log(data);
            this.drawChart2(data);
        }
    }


    drawChart2(data) {
        var chart_data = [[],[],[], []];
        for(var day in data) {
            chart_data[1].push(parseInt(data[day][0]));
            chart_data[2].push(parseInt(data[day][1]));
            chart_data[3].push(day);
            chart_data[0].push(day);
        }

        Highcharts.chart(this.chart_identifier, {
            chart: {
                zoomType: 'xy'
            },
            title: {
                text: name
            },
            xAxis: [{
                categories: chart_data[0],
                crosshair: true
            }],

            tooltip: {
                shared: true
            },
            series: [{
                name: 'This Week',
                type: 'area',
                data: chart_data[1],
                color: '#80ccff',
            },
                {
                    name: 'Before Week',
                    type: 'spline',
                    data: chart_data[2],
                    color: '#ff751a',
                }]
        });
    }
}



$(document).ready(function() {
    $('.tablinks')[0].click();
    //google.charts.setOnLoadCallback(drawChart);
    new Controller('evolutionChart');
});
