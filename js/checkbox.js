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
    console.log("hledej");
    drawn = true;
    drawChart(drawn);
  });

});
