//ziskam hodnoty z formualre
function getDataString() {

  var from = document.getElementById('input_from').value;
  var where = document.getElementById('input_where').value;
  var date = document.getElementById('input_date').value;
  var os = "no";
  var sp = "no";
  var both = "no";
  var there = "no";

  if (document.getElementById('switch_os').checked){
      os = "yes";
  }
  if (document.getElementById('switch_sp').checked){
      sp = "yes";
  }
  if (document.getElementById('switch_both').checked){
      both = "yes";
  }
  if (document.getElementById('switch_there').checked){
      there = "yes";
  }

  //vytvorim POST
  var dataString = 'from='+from+'&where='+where+'&date='+date+'&os='+os+'&sp='+sp+'&both='+both+'&there='+there;
  //alert(dataString);
  console.log(dataString);
  return dataString;
}
