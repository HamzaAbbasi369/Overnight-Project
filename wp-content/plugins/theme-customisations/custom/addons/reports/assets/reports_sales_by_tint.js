/**
 * google-charts
 * reports_sales_by_tint
 */

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawVisualization);

function drawVisualization() {

    if(typeof rx_admin_params.reports_sales_by_tint !== 'undefined') {

        var data = google.visualization.arrayToDataTable(JSON.parse(rx_admin_params.reports_sales_by_tint));

        var options = {
            title : 'Reports sales by tint',
            is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
}
