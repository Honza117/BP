//Ajax obsluha formulare
function drawChart(drawn) {

        //drawn = true; //Graf vykreslen
        var dataString = getDataString();

        //Asynchrone zavolam php
        function drawVisualization() {

          //Ziskam JSON data
          var jsonData = getJSON(dataString, drawn);

          //Pokud se nema co vykreslit
          if (jsonData == -1){
            return;
          }

          //Nastavim vlastnosti grafu
          var options = getOptions(dataString);

          //alert(jsonData);

          //Listener

          // Create our data table out of JSON data loaded from server.
          var data = new google.visualization.DataTable(jsonData);
          // Instantiate and draw our chart, passing in some options.
          var chart = new google.visualization.LineChart(document.getElementById('chart'));
          google.visualization.events.addListener(chart, 'ready', myReadyHandler);
          chart.draw(data, options);

          function myReadyHandler() {
            $("#loading_gif").css("visibility","hidden");
            $("#chart").css("visibility","visible");
          }

        }

        google.charts.load('current', {packages: ['corechart'], 'language': 'cs'}); //load packages for particular chart types - load(version, corechart=bar,column,line,...)
        google.charts.setOnLoadCallback(drawVisualization);
};
