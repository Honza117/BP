function getLayout(dataString){

    var zoomX = true;  //Primarne se zoomuje jen osa X

    var y_direction = $.ajax({
          type: "post",
          url: "php/getDirection.php",
          data: dataString,
          dataType: "json",
          cache: false,
          async: false,
    }).responseText;

     var my_labels = $.ajax({
          type: "post",
          url: "php/getLabelsY.php",
          data: dataString,
          dataType: "json",
          cache: false,
          async: false,
      }).responseJSON;

      var my_ticks = $.ajax({
          type: "post",
          url: "php/getTicksY.php",
          data: dataString,
          dataType: "json",
          cache: false,
          async: false,
      }).responseJSON;

      var my_direct = '';

      if (y_direction == -1){
          my_direct = "reversed";

      }
      else{
          my_direct = true;
      }

      if (document.getElementById('switch_zoom').checked){
        zoomX = false;
      }


    var layout = {
            margin: {
                l: 180,
                r: 100,
                b: 20,
                t: 0,
                pad: 4
            },
            yaxis: {
                tickvals: my_ticks,//[0, 22, 38, 47, 57, 74, 91, 101, 116, 151, 193, 250, 255],  //Ziskat vzdalenosti (jako my_labels)
                ticktext: my_labels,//["Brno hl.n.", "Blansko", "Skalice nad Svitavou", "Letovice", "Březová nad Svitavou", "Svitavy", "Česká Třebová", "Ústí nad Orlicí", "Choceň", "Pardubice hl.n.", "Kolín", "Praha-Libeň", "Praha hl.n."],// //['Brno', 'Černovice', 'Slatina'],
                tickfont: { size: 12 },
                autorange: my_direct,
                side: 'left',
                fixedrange: zoomX  //Pri tomto pouziti se zoomuje pouze osa X
            },
            yaxis2: {
                tickvals: my_ticks,
                //ticktext: reverse_ticks,
                //ticktext: my_labels,//["Brno hl.n.", "Blansko", "Skalice nad Svitavou", "Letovice", "Březová nad Svitavou", "Svitavy", "Česká Třebová", "Ústí nad Orlicí", "Choceň", "Pardubice hl.n.", "Kolín", "Praha-Libeň", "Praha hl.n."],// //['Brno', 'Černovice', 'Slatina'],
                overlaying: 'y',
                side: 'right',
                tickfont: { size: 12 },
                autorange: my_direct,
                fixedrange: zoomX  //Pri tomto pouziti se zoomuje pouze osa X
            },
            /*yaxis3: {
                tickvals: my_ticks,
                anchor: 'free',
                overlaying: 'y',
                side: 'right',
                tickfont: { size: 12 },
                autorange: my_direct,
                fixedrange: true,  //Pri tomto pouziti se zoomuje pouze osa X
                position: 0.85
            },*/
            showlegend: false,
            dragmode: 'pan'
        }
    return layout;
}


