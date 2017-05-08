function getLayout(dataString){

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

      console.log(my_labels);
      console.log(my_ticks);

    var layout = {
            margin: {
                    l: 180,
                    r: 180,
                    b: 20,
                    t: 5,
                    pad: 0
            },
            yaxis: {
                tickvals: my_ticks,//[0, 22, 38, 47, 57, 74, 91, 101, 116, 151, 193, 250, 255],  //Ziskat vzdalenosti (jako my_labels)
                ticktext: my_labels,//["Brno hl.n.", "Blansko", "Skalice nad Svitavou", "Letovice", "Březová nad Svitavou", "Svitavy", "Česká Třebová", "Ústí nad Orlicí", "Choceň", "Pardubice hl.n.", "Kolín", "Praha-Libeň", "Praha hl.n."],// //['Brno', 'Černovice', 'Slatina'],
                tickfont: { size: 12 },
                autorange: my_direct,
                fixedrange: true  //Pri tomto pouziti se zoomuje pouze osa X
            },
            showlegend: false
        }
    return layout;
}


