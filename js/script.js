//Ajax obsluha formulare
function drawChart(drawn) {

        var dataString = getDataString();
        //alert(dataString);
        console.log(dataString);
        //var req = "getDataPlotly.php?" + dataString;
        //window.history.pushState(dataString, "něco", req);
        //Ajax spusti php skript
        var data = getJSON(dataString, drawn);
        //console.log(data);
        var chart = document.getElementById('chart');
        var layout = getLayout(dataString);

        Plotly.newPlot(chart, data, layout, {scrollZoom: true});
}
