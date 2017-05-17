//ziskam hodnoty z formualre
function getDataString() {
  
  var track = document.getElementById('input_track').value;
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
  var zoom = "no";

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
  if (document.getElementById('switch_zoom').checked){
      zoom = "yes";
  }

  if (from.length < 1){
      from = document.getElementById('first').value;
  }

  if (where.length < 1){
      where = document.getElementById('last').value;
  }

  //vytvorim POST
  var dataString = 'track='+track+'&from='+from+'&where='+where+'&date='+date+'&os='+os+'&sp='+sp+'&ex='+ex+'&rx='+rx+'&rj='+rj+'&ec='+ec+'&en='+en+'&both='+both+'&there='+there+'&zoom='+zoom;

  console.log(dataString);
  return dataString;
}

//Nastaveni konfigurace podle sdileneho retezce
function fillForm(shareData){
    var equCnt = 0; //pocitadlo "="
    var ampCnt = 0; //pocitadlo "&"
    var verbCnt = 0; //pocitadlo slov
    var isverb = false;
    var track = "";
    var from = "";
    var where = "";
    var date = "";
    var os = "";
    var sp = "";
    var ex = "";
    var rx = "";
    var rj = "";
    var ec = "";
    var en = "";
    var both = "";
    var there = "";
    var zoom = "";

    for (var i = 0; i < shareData.length; i++){
        var c = shareData.charAt(i); //Cteme po znaku
        
        if (c == "="){
            equCnt++;
            verbCnt++;
            isverb = true;
            continue;
        }
        if (c == "&"){
            ampCnt++;
            isverb = false;
            continue;
        }
        
        if (isverb){
            switch(verbCnt){
            case 1:
                track += c;
                break;
            case 2:
                from += c;
                break;
            case 3:
                where += c;
                break;
            case 4:
                date += c;
                break;
            case 5:
                os += c;
                break;
            case 6:
                sp += c;
                break;
            case 7:
                ex += c;
                break;
            case 8:
                rx += c;
                break;
            case 9:
                rj += c;
                break;
            case 10:
                ec += c;
                break;
            case 11:
                en += c;
                break;
            case 12:
                both += c;
                break;
            case 13:
                there += c;
                break;
            case 14:
                zoom += c;
                break;
            } 
        }
    }
    //console.log("parsovani: " + from + " " + where + " " + date + " " + os + " " + sp + " " + ex + " " + rx + " " + rj + " " + ec + " " + en + " " + both + " " + there + " " + zoom);
    //Nastavim hodnoty
    document.getElementById('input_track').value = track;
    document.getElementById('input_from').value = from;
    document.getElementById('input_where').value = where;
    if (os == "no"){
      document.getElementById('switch_os').checked = false;
    }
    if (sp == "no"){
      document.getElementById('switch_sp').checked = false;
    }
    if (ex == "no"){
      document.getElementById('switch_ex').checked = false;
    }
    if (rx == "no"){
      document.getElementById('switch_rx').checked = false;
    }
    if ( rj == "no"){
      document.getElementById('switch_rj').checked = false;
    }
    if (ec == "no"){
      document.getElementById('switch_ec').checked = false;
    }
    if (en == "no"){
      document.getElementById('switch_en').checked = false;
    }
    if (both == "yes"){
      document.getElementById('switch_both').checked = true;
    }
    if (there == "no"){
      document.getElementById('switch_there').checked = false;
    }
    if (zoom == "yes"){
      document.getElementById('switch_zoom').checked = true;
    }
}
