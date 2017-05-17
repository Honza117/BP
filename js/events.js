$(function(){

    var drawn = false; //Znaci, ze byl graf vykresleny a bude se prekreslovat
    var shared = false; //Znaci, ze byl graf sdileny
    var shareData = window.location.href; //Retezec URL adresy

    //Pokud je soucasti adresy take retezec pro sdileni
    if ((shareData.indexOf("?") > 0) && (!shared)){
        console.log("mam data");
        shareData = shareData.substring(shareData.indexOf("?")+1); //vyberu pouze data
        shareData = shareData.split("%20").join(' '); //nahradim %20 mezerami
        drawn = true; //Vykreslen
        drawChart(drawn, shareData, shared); //vykreslim graf
        shared = true; //Sdilen
    }
    else{
        shareData = "";
    }

    //Sdílení
    $("#share").click(function(){
        console.log("Sdílení");
        var shareString = getDataString();
        shareString = "http://www.jupw.cz/?"+shareString;
        shareString = shareString.split(' ').join("%20");
        window.prompt("Konfigurace grafu:", shareString);
    });

    //Refresh
    $("#refresh").click(function(){
        console.log("Obnova");
        window.location.href = 'http://www.jupw.cz';
    });

    //vyhledavani Entrem
    $(document).bind('keypress', function(e) {
        if(e.keyCode==13){
            $('#subBtn').trigger('click');
        }
    });

    //Zobrazi data podle prepinace - pokud graf vykreslen, prekresli
    $("input.types").change(function(){
        if (drawn){
            $('#subBtn').trigger('click');
        }
    });

    //Vykreslení
    $("#subBtn").click(function(){
        console.log("hledej");
        drawn = true;
        drawChart(drawn, shareData, shared);
    }); 
});
