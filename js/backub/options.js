function getOptions(dataString) {

  //Zjistim smer osy Y : 1 / -1
  var y_direct = $.ajax({
          type: "post",
          url: "php/getDirection.php",
          data: dataString,
          dataType: "json",
          cache: false,
          async: false,
  }).responseText;

  //Zjistim hodnoty pro Y a zaroven hodnotam 'v' pridam jmena 'f'
  var my_labels = $.ajax({
          type: "post",
          url: "php/getLabelsY.php",
          data: dataString,
          dataType: "json",
          cache: false,
          async: false,
      }).responseJSON;

  //Nastaveni grafu
  var options = {
      curveType: "none",
      width: '100%',
      height: '100%',
      chartArea: {
          top: 10,
          height: '85%'
      },
      height: 480,
      interpolateNulls: true,
      vAxis: { //Osa Y
          direction: y_direct,
          ticks: my_labels,
      },
      hAxis: { //Osa X
          gridlines: {
              count: 76
          },
          minValue: new Date(2017, 3, 12, 0, 0, 0),  // bude zobrazen cely provozni den na trati
          maxValue: new Date(2017, 3, 15, 0, 0, 0)
      },
      explorer: {
          //actions: ['dragToZoom', 'rightClickToReset'],
          axis: 'horizontal',
          keepInBounds: true,
          maxZoomIn: 10.0,
          maxZoomOut: 8.0,
      },
      legend: {
          position: 'none'
      },
      tooltip: {
          isHtml: true
      }
  };
  return options;
}
