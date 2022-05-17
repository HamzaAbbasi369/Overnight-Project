/**
 * google-charts
 * reports_sales_by_package
 */

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawVisualization);

function drawVisualization() {

    if(typeof rx_admin_params.reports_sales_by_package !== 'undefined') {

        var data = google.visualization.arrayToDataTable(JSON.parse(rx_admin_params.reports_sales_by_package));

        var options = {
            title: 'Sales by package',
            curveType: 'function',
            legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

        chart.draw(data, options);


    }
}
