$(function(){

  //Kontroal pokud bude scroll nad grafem
  $("g").hover(function() {
    console.log("hover");
    $(window).scroll(function() {
      var scroll = $(window).scrollTop();
      var position = 0;
      if (scroll > position) {
        console.log("scrolling downwards");
      } else {
        console.log("scrolling upwards");
      }
      position = scroll;
    });
  })
});
