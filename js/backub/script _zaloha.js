//Ajax obsluha formulare
$(document).ready(function(){

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
        //Ajax spusti php skript
        $.ajax({
            type: "post",
            url: "php/getData.php",
            data: dataString,
            cache: false,
            success: function(html){
                $("#msg").html(html);
            }
        });//.done(function(){alert("Skritp spusten");});
    });

    //Naseptavac
    $('#input_from').devbridgeAutocomplete({
        serviceUrl: 'php/whisperer.php'
    });
    $('#input_where').devbridgeAutocomplete({
        serviceUrl: 'php/whisperer.php'
    });

});

