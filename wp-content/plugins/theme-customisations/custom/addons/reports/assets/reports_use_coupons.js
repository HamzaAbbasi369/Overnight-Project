/**
 * google-charts
 * reports_sales_by_tint
 */

google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawVisualization);

function drawVisualization() {

    if(typeof rx_admin_params.reports_use_coupons !== 'undefined') {

        var data = google.visualization.arrayToDataTable(JSON.parse(rx_admin_params.reports_use_coupons));

        var options = {
            title : 'Coupon usage reports',
            is3D: true
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
}
