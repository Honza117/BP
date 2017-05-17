//Ajax obsluha formulare
function drawChart(drawn, shareData, shared) {

	//Pokud se nejedna o sdileni
    if ((shareData.length < 1) || (shared)){
  	   	var dataString = getDataString();
		console.log("nesdilim");
    }
	//Pokud se jedna o sdileni
    else if ((shareData.length > 1) && (!shared)){
        dataString = shareData;
		fillForm(shareData);
		console.log("sdilim");
    }
    
    //Ajax spusti php skript
    var data = getJSON(dataString, drawn);
    var chart = document.getElementById('chart');
    var layout = getLayout(dataString);

    Plotly.newPlot(chart, data, layout, {scrollZoom: true});
}
