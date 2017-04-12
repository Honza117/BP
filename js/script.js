//Ajax obsluha formulare
$(function(){

    var drawn = false; //Znaci, ze byl graf vykresleny a bude se prekreslovat

    //vyhledavani Entrem
    $(document).bind('keypress', function(e) {
        if(e.keyCode==13){
            $('#subBtn').trigger('click');
        }
    });

    //Zobrazi os - pokud graf vykreslen, prekresli
    $("#switch_os").change(function(){
        if (drawn){
            $('#subBtn').trigger('click');
        }
    });

    //Zobrazi sp - pokud graf vykreslen, prekresli
    $("#switch_sp").change(function(){
        if (drawn){
            $('#subBtn').trigger('click');
        }
    });

    //Zobrazi zpatecni - pokud je graf vykreslen
    $("#switch_both").change(function(){
        if (drawn){
            $('#subBtn').trigger('click');
        }
    });

    $("#subBtn").click(function(){

        drawn = true; //Graf vykreslen

        //ziskam hodnoty z formualre
        var from = document.getElementById('input_from').value;
        var where = document.getElementById('input_where').value;
        var date = document.getElementById('input_date').value;
        var os = "no";
        var sp = "no";
        var both = "no"; 

        if (document.getElementById('switch_os').checked){
            os = "yes";
        }
        if (document.getElementById('switch_sp').checked){
            sp = "yes";
        }
        if (document.getElementById('switch_both').checked){
            both = "yes";
        }

        //vytvorim POST 
        var dataString = 'from='+from+'&where='+where+'&date='+date+'&os='+os+'&sp='+sp+'&both='+both;
        alert(dataString);
        console.log(dataString);

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

        //Asynchrone zavolam php
        function drawVisualization() {
            var jsonData = $.ajax({
            type: "post",
            url: "php/getData.php",
            data: dataString,
            dataType: "json",
            cache: false,
            async: false,
            success: function(html){
                $("#msg").html(html);
            }
        }).responseText;

        //Kontrola, pokud se nevrati zadna data a graf neni vykreslen
        if ((jsonData.search("0 results") != -1) && ((from == "") || (where == ""))){
            return;
        }

        //Kontrola, pokud se nevrati zadna data a graf je vykreslen
        if ((jsonData.search("0 results") != -1) && (drawn)){
            jsonData = {
                "cols": [
                    {"id":"","label":"Time","pattern":"","type":"datetime"},
                    {"id":"","label":"Vlak","pattern":"","type":"number"}
                ],
                "rows": [
                    {"c":[{"v":"Date(2017,03,13,12,0,0)","f":null},{"v":0,"f":null}]},
                ]
            };
        }

            //Nastaveni grafu
            var options = {
                curveType: "none",
                width: 1800,
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
                    minValue: new Date(2017, 3, 13, 4, 0, 0),  // bude zobrazen cely provozni den na trati
                    maxValue: new Date(2017, 3, 13, 24, 0, 0)
                },
                /*animation:{
                    duration: 1500,
                    easing: 'linear',  //Pokud je zapla animace, nefunguje zoom!!
                    startup: true
                },*/
                explorer: {
                    axis: 'horizontal',
                    keepInBounds: true,
                    maxZoomIn: 10.0
                },
                legend: {
                    position: 'none'
                },
                tooltip: {
                    isHtml: true
                }
            };
          
            alert(jsonData);
            // Create our data table out of JSON data loaded from server.
            var data = new google.visualization.DataTable(jsonData);

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.LineChart(document.getElementById('chart'));
            chart.draw(data, options);
        }

        google.charts.load('current', {packages: ['corechart'], 'language': 'cs'}); //load packages for particular chart types - load(version, corechart=bar,column,line,...)
        google.charts.setOnLoadCallback(drawVisualization);

    });
});

