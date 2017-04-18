$(function(){

  var drawn = false; //Znaci, ze byl graf vykresleny a bude se prekreslovat

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

  $("#subBtn").click(function(){
    $("#chart").css("visibility","hidden"); //Skryje původní graf
    $("#loading_gif").css("position","relative"); //Zobrazi gif pro animaci
    $("#loading_gif").css("visibility","visible"); //Zobrazi gif pro animaci
    setTimeout(function(){
      drawChart(drawn);
    }, 200);
    drawn = true;
  });

});
