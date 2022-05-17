/**
 * google-charts
 * sales_by_prescription_type
 */

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawVisualization);

function drawVisualization() {

    if(typeof rx_admin_params.sales_by_prescription_type !== 'undefined') {

        var data = google.visualization.arrayToDataTable(JSON.parse(rx_admin_params.sales_by_prescription_type));

        var options = {
            title: 'Reports sales by prescription type',
            is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);

    }
}
