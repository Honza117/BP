<?php

/*
 * Obsluha formulare - kontrola
*/
$from = $_POST["from"];
$where = $_POST["where"];
$date = $_POST["date"];
$date = "2017-03-13"; //TESTOVACI HODNOTA
$both = $_POST["both"];
$there = $_POST["there"];

$enable_types = array(); //Pole povolenych typu vlaku
$enable_types["os"] = $_POST["os"];
$enable_types["sp"] = $_POST["sp"];
$enable_types["ex"] = $_POST["ex"];
$enable_types["rx"] = $_POST["rx"];
$enable_types["rj"] = $_POST["rj"];
$enable_types["ec"] = $_POST["ec"];
$enable_types["en"] = $_POST["en"];

/*
 * Obsluha databaze
*/
$host = "wm145.wedos.net";
$username = "w158163_spoje";
$password = "E5TG7aSx";
$dbname = "d158163_spoje";

//Pripojeni k databazi klientem pro cteni
$con = mysqli_connect($host,$username,$password,$dbname);
if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}

//Změna charsetu na utf8
mysqli_set_charset($con,"utf8");

//Zjisteni ID Odkud
$qry = "SELECT trat_id, vzdalenost FROM stanice WHERE jmeno='$from'";
$result = mysqli_query($con,$qry);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $from_id = intval($row["trat_id"]); //id stanice Odkud
        $from_length = intval($row["vzdalenost"]); //vzdalenost na trati
    }
} else {
    echo "0 results A";
}

//Zjisteni ID Kam
$qry = "SELECT trat_id, vzdalenost FROM stanice WHERE jmeno='$where'";
$result = mysqli_query($con,$qry);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $where_id = intval($row["trat_id"]); //id stanice Kam
        $where_length = intval($row["vzdalenost"]); //vzdalenost na trati
    }
} else {
    echo "0 results B";
}

/*
 * Pokud shoda
*/
if ($from_id == $where_id){

    $qry = "SELECT delka FROM trate WHERE id='$from_id'";
    $result = mysqli_query($con,$qry);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $length = intval($row["delka"]); //celkova delka trate
            //echo "Celkova vzdalenost: " . $length . "<br>" . "vzdalenost Odkud: " . $from_length . "<br>" . "vzdalenost Kam: " . $where_length . "<br>";
        }
    } else {
        echo "0 results C";
    }

    //Zjistim vsechny stanice mezi A a B
    $desc = false; //Pokud je opacny smer, preusporadam pole
    if ($from_length < $where_length){
        $qry = "SELECT id, jmeno, vzdalenost FROM stanice WHERE trat_id='$from_id' AND vzdalenost BETWEEN $from_length AND $where_length ORDER BY vzdalenost"; //od nejmensi po nejvetsi
        $desc = false;
    }
    else {
        $qry = "SELECT id, jmeno, vzdalenost FROM stanice WHERE trat_id='$from_id' AND vzdalenost BETWEEN $where_length AND $from_length ORDER BY vzdalenost DESC"; //od nejvetsi po nejmensi
        $desc = true;
    }
    $result = mysqli_query($con,$qry);
    $stations = array(); //Stanice serazene podle vzdalenost
    $stations_length = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            //echo "jmeno: " . $row["jmeno"] . " " .  "vzdalenost: " . $row["vzdalenost"]. "<br>";
            $stations[intval($row["id"])] = $row["jmeno"];
            $stations_length[intval($row["id"])] = intval($row["vzdalenost"]);
        }
    } else {
        echo "0 results D";
    }

    //Pokud zobrazit oba smery
    if ($both == "yes"){
        if ($from_length < $where_length){
            $qry = "SELECT id, jmeno, vzdalenost FROM stanice WHERE trat_id='$from_id' AND vzdalenost BETWEEN $from_length AND $where_length ORDER BY vzdalenost DESC"; //od nejvetsi po nejmensi
        }
        else {
            $qry = "SELECT id, jmeno, vzdalenost FROM stanice WHERE trat_id='$from_id' AND vzdalenost BETWEEN $where_length AND $from_length ORDER BY vzdalenost"; //od nejmensi po nejvetsi
        }
        //echo "Dotaz: " . $qry . "<br>";
        $result = mysqli_query($con,$qry);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
               // echo "jmeno: " . $row["jmeno"] . " " .  "vzdalenost: " . $row["vzdalenost"]. "<br>";
                $stations[intval($row["id"])] = $row["jmeno"];
            }
        } else {
            echo "0 results D02";
        }
    }
}

$trains = array(); //Hlavni pole pro ulozeni vlaku a informacim k nim
$id_opposite = array(); //Pole pro ulozeni opacnych vlaku pri vykreslovani obou smeru

$qry_member = ""; //Retezec obsahujici typy vlaku urcenych pro vykresleni
$enable_types_cnt = 0;
//Pres vsechny povolene typy vytvorim podretezec qry
foreach ($enable_types as $type => $value) {
    $enable_types_cnt++;
    if ($value == "yes"){
        if ($enable_types_cnt == 1){
            $qry_member = $qry_member . " AND (V.typ='{$type}'";
        }
        if ($enable_types_cnt > 1){
            $qry_member = $qry_member . " OR V.typ='{$type}'";
        }
    }
}

//Vsechny spoje ID v den=$date k vlakum ID pres stanice ID
foreach ($stations as $key => $value){
    //Podle tabulky smeru vyberu jen pozadovany smer
    if (!$desc){ //Pokud smer = s
        if (($both == "yes") && ($there == "yes")){
            $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id, P.cislo_spoje FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND (S.provozni_den='2017-03-13' OR S.provozni_den='2017-03-14') AND S.vlak_id=V.id".$qry_member .")";
        }
        else if (($both == "no") && ($there == "yes")){
            $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id, P.cislo_spoje FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND (S.provozni_den='2017-03-13' OR S.provozni_den='2017-03-14') AND P.smer_id=M.id AND M.smer='s' AND S.vlak_id=V.id".$qry_member.")";
        }
        else if (($both == "yes") && ($there == "no")){
            $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id, P.cislo_spoje FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND (S.provozni_den='2017-03-13' OR S.provozni_den='2017-03-14') AND P.smer_id=M.id AND M.smer='j' AND S.vlak_id=V.id".$qry_member.")";
        }
    }
    else { //Pokud smer = j
        if (($both == "yes") && ($there == "yes")){
            $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id, P.cislo_spoje FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND (S.provozni_den='2017-03-13' OR S.provozni_den='2017-03-14') AND S.vlak_id=V.id".$qry_member.")";
        }
        else if (($both == "no") && ($there == "yes")) {
            $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id, P.cislo_spoje FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND (S.provozni_den='2017-03-13' OR S.provozni_den='2017-03-14') AND P.smer_id=M.id AND M.smer='j' AND S.vlak_id=V.id".$qry_member.")";
        }
        else if (($both == "yes") && ($there == "no")){
            $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id, P.cislo_spoje FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND (S.provozni_den='2017-03-13' OR S.provozni_den='2017-03-14') AND P.smer_id=M.id AND M.smer='s' AND S.vlak_id=V.id".$qry_member.")";
        }
    }
    
    $result = mysqli_query($con,$qry);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            //echo "vlak id: " . $row["vlak_id"] . " " .  "id: " . $row["id"]. " " . "cislo spoje: " . $row["cislo"] . "<br>";
            $trains[intval($row["vlak_id"])][intval($row["cislo_spoje"])][] = intval($row["id"]); //vlak => id => cislo spoje => spoje

            //Pokud smer s, ulozim id vlaku se smerem j
            if (((!$desc && $both) && (intval($row["smer_id"]) == 2) && (!in_array(intval($row["vlak_id"]), $id_opposite)))){
                //$trains_j[] = intval($row["vlak_id"]);
                $id_opposite[] = intval($row["vlak_id"]);
            }

            //Pokud smer j, ulozim id vlaku se smerem s
            if ((($desc && $both) && (intval($row["smer_id"]) == 1) && (!in_array(intval($row["vlak_id"]), $id_opposite)))){
                //$trains_s[] = intval($row["vlak_id"]);
                $id_opposite[] = intval($row["vlak_id"]);
            }

        }
    } /*else {
        echo "0 results E"; //Zakodovanu kvuli spojum SP (mivaji 0 results)
    }*/
}

//Ulozim ke kazdemu vlaku jeho cislo
foreach ($trains as $key => $value){

    $qry = "SELECT DISTINCT cislo, jmeno, typ FROM vlak WHERE id='$key'";
    $result = mysqli_query($con,$qry);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            //echo "id: " . $key . " " .  "cislo vlaku: " . $row["cislo"] . "<br>";
            $trains[$key]["cislo"] = intval($row["cislo"]);
            $trains[$key]["jmeno"] = $row["jmeno"];
            $trains[$key]["typ"] = $row["typ"];
        }
    } else {
        echo "0 results F";
    }
}

//Vytvorim data pro y (kazda vzdalenost musi byt 2x - cas odjezdu / prijezdu)
$counter = 0;
$data_y = array();
foreach ($stations_length as $key => $value){
    $data_y[$counter] = (string)$value;
    $counter++;
    $data_y[$counter] = (string)$value;
    $counter++;
}

foreach ($trains as $train_id => $train_inf){
    foreach ($train_inf as $conn_num => $con_arr){
        if (!is_int($conn_num)){  //Chci jen pole s ID prijezdu
             continue;
        }
         foreach($con_arr as $key => $arr_id){
            
            //Zjistim cas
            $qry="SELECT DISTINCT P.prijezd, P.odjezd, P.id, S.provozni_den FROM prijezdy P JOIN spoje S WHERE S.id='$arr_id' AND S.prijezd_id=P.id";
            $result = mysqli_query($con,$qry);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    //echo "id: " . $value . " " . "prijezd: " . $row["prijezd"] .  "odjezd: " . $row["odjezd"] . "<br>";
                    $arr = $row["prijezd"];
                    $dep = $row["odjezd"];
                    $date = $row["provozni_den"];

                    if (strlen($arr) < 1) { //Osetreni hodnot NULL
                        $arr = "null";
                    }
                    if (strlen($dep) < 1) {
                        $dep = "null";
                    }

                    $p_id = intval($row["id"]); //id prijezdu pro zjisteni vzdalenosti
                }
            } else {
                echo "0 results G";
            }
            //Ulozim ke kazdemu vlaku jeho casy
            $trains[$train_id]["casy"][] = $arr;
            $trains[$train_id]["casy"][] = $dep;
            //A ke kazdemu casu datum
            $trains[$train_id]["datum"][] = $date;
            $trains[$train_id]["datum"][] = $date;
         }
     }
}

/*
 * Vytvoreni JSON dat ---- Plotly.js
*/
//Vytvoreni dat pro osu y - vzdalenosti zastavek (kazda 2x)
$data = array(); //Konecna data pro JSON kodovani
$data_member = array(); //Casti tvorici data
foreach ($trains as $key => $value){
    $counter = 0; //Pocitadlo pro spravne datum k odjezdu/prijezdu
    $train_type = $trains[$key]["typ"];
    $train_name = $trains[$key]["jmeno"];
    $train_num = $trains[$key]["cislo"];
    $data_member["mode"] = "scatter";
    $data_member["y"] = $data_y; //kazda 2x (lze vytvorit na zacatku skriptu když hledam stanice)

    $data_time = array();
   
    foreach ($trains[$key]["casy"] as $time){
        $data_member["text"][$counter] = $train_type . " " . $train_num . "<br>" . $train_name . "<br>" . $time;

        $date = $trains[$key]["datum"][$counter]; //Vyberu datum (pulnocni vlaky dojedou druhy den)
        if ($time == "null"){
            $data_time[] = null;
        }
        else{
            $data_time[] = $date . ' ' . $time;
        }
        $counter++;
    }
    //Pokud je vlak v opacnem smeru, musime prohodit jeho odjezd/prijezd kvuli vykresleni
    if (in_array($key, $id_opposite)){
        $foo = "";
        for ($i = 0; $i < count($data_time); $i++){
            if ($i%2 == 0){ //sude
                $foo = $data_time[$i];
            }
            if ($i%2 == 1){ //liche
                $data_time[$i-1] = $data_time[$i];
                $data_time[$i] = $foo;
            }
        }
    }
    $data_member["x"] = $data_time;
    //Nastaveni barvy vlaku podle typu
    switch($train_type){
        case "OS":
             $data_member["line"]["color"] = "rgb(0,87,231)";
            break; 
        case "SP":
             $data_member["line"]["color"] = "rgb(0,87,231)";
            break;
        case "Ex":
             $data_member["line"]["color"] = "rgb(0,87,231)";
            break;
        case "Rx":
             $data_member["line"]["color"] = "rgb(0,87,231)";
            break;
        case "RJ":
             $data_member["line"]["color"] = "rgb(0,87,231)";
            break;
        case "EC":
            $data_member["line"]["color"] = "rgb(0,135,68)";
            break;
        case "EN":
             $data_member["line"]["color"] = "rgb(214,45,32)";
            break;
        default:
            break;
    }
    //$data_member["line"]["width"] = 3;
    $data_member["type"] = "scatter";
    $data_member["name"] = $train_name; //Jmeno vlaku
    //$data_member["text"] = ["Vlak 222<br>22:23:00", "Vlak 222<br>22:28:00", "Vlak 222<br>22:35:00"]; //sem doplnim text jako dole tooltip
    $data_member["hoverinfo"] = "text";
    $data_member["connectgaps"] = true;
    $data[] = $data_member;
}
$json_data = json_encode($data, true);
echo $json_data;
?>
