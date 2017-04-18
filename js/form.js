//ziskam hodnoty z formualre
function getDataString() {

  var from = document.getElementById('input_from').value;
  var where = document.getElementById('input_where').value;
  var date = document.getElementById('input_date').value;
  var os = "no";
  var sp = "no";
  var ex = "no";
  var rx = "no";
  var rj = "no";
  var ec = "no";
  var en = "no";
  var both = "no";
  var there = "no";

  if (document.getElementById('switch_os').checked){
      os = "yes";
  }
  if (document.getElementById('switch_sp').checked){
      sp = "yes";
  }
  if (document.getElementById('switch_ex').checked){
      ex = "yes";
  }
  if (document.getElementById('switch_rx').checked){
      rx = "yes";
  }
  if (document.getElementById('switch_rj').checked){
      rj = "yes";
  }
  if (document.getElementById('switch_ec').checked){
      ec = "yes";
  }
  if (document.getElementById('switch_en').checked){
      en = "yes";
  }
  if (document.getElementById('switch_both').checked){
      both = "yes";
  }
  if (document.getElementById('switch_there').checked){
      there = "yes";
  }

  //vytvorim POST
  var dataString = 'from='+from+'&where='+where+'&date='+date+'&os='+os+'&sp='+sp+'&ex='+ex+'&rx='+rx+'&rj='+rj+'&ec='+ec+'&en='+en+'&both='+both+'&there='+there;
  //alert(dataString);
  console.log(dataString);
  return dataString;
}
