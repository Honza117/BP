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
    drawChart(drawn);
    drawn = true;
  });

});
