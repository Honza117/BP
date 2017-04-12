function getJSON(dataString, drawn) {
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

  //Kontrola, pokud se nevrati zadna data a graf je vykreslen
  if ((jsonData.search("0 results") != -1) && ((document.getElementById('input_from').value == "") || (document.getElementById('input_where').value == ""))){
    return -1;
  }

  //Nulova data pokud se ma vykreslit prazdny graf
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

  return jsonData;
}
