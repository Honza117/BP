<?php

/*
 * Obsluha formulare - kontrola
*/
$from = $_POST["from"];
$where = $_POST["where"];
$date = $_POST["date"];
$date = "2017-03-13"; //TESTOVACI HODNOTA
$os = $_POST["os"];
$sp = $_POST["sp"];
$both = $_POST["both"];
$there = $_POST["there"];
//echo "Odkud: " . $from . "<br>" . "Kam: " . $where . "<br>" . "Kdy: " . $date . "<br>";

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
//echo "Dotaz: " . $qry . "<br>";
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
//echo "Dotaz: " . $qry . "<br>";
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
    //echo "Dotaz: " . $qry . "<br>";
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
    //echo "Dotaz: " . $qry . "<br>";
    $result = mysqli_query($con,$qry);
    $stations = array(); //Stanice serazene podle vzdalenost
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            //echo "jmeno: " . $row["jmeno"] . " " .  "vzdalenost: " . $row["vzdalenost"]. "<br>";
            $stations[intval($row["id"])] = $row["jmeno"];
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

//print_r($stations);
//echo "<br>";

$os_train_color = array(); //Pole pro ulozeni id os vlaku kvuli vybarveni (modrou)
$trains = array();
$id_opposite = array(); //Pole pro ulozeni opacnych vlaku

//Vsechny spoje ID v den=$date k vlakum ID pres stanice ID
foreach ($stations as $key => $value){
    //Podle tabulky smeru vyberu jen pozadovany smer
    if (!$desc){ //Pokud smer = s
        if (($os == "yes") && ($sp == "no")){
            if (($both == "yes") && ($there == "yes")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id, P.cislo_spoje FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND S.vlak_id=V.id AND V.typ='os'";
            }
            else if (($both == "no") && ($there == "yes")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id, P.cislo_spoje FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND P.smer_id=M.id AND M.smer='s' AND S.vlak_id=V.id AND V.typ='os'";
            }
            else if (($both == "yes") && ($there == "no")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id, P.cislo_spoje FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND P.smer_id=M.id AND M.smer='j' AND S.vlak_id=V.id AND V.typ='os'";
            }
        }
        else if (($sp == "yes") && ($os == "no")){
            if (($both == "yes") && ($there == "yes")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id, P.cislo_spoje FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND S.vlak_id=V.id AND V.typ='sp'";
            }
            else if (($both == "no") && ($there == "yes")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id, P.cislo_spoje FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND P.smer_id=M.id AND M.smer='s' AND S.vlak_id=V.id AND V.typ='sp'";
            }
            else if (($both == "yes") && ($there == "no")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id, P.cislo_spoje FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND P.smer_id=M.id AND M.smer='j' AND S.vlak_id=V.id AND V.typ='sp'";
            }
        }
        else if (($sp == "yes") && ($os == "yes")) {
            if (($both == "yes") && ($there == "yes")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, P.smer_id, V.typ FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND S.vlak_id=V.id";
            }
            else if (($both == "no") && ($there == "yes")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, P.smer_id, V.typ FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND P.smer_id=M.id AND M.smer='s' AND S.vlak_id=V.id";
            }
            else if (($both == "yes") && ($there == "no")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, P.smer_id, V.typ FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND P.smer_id=M.id AND M.smer='j' AND S.vlak_id=V.id";
            }
        }
    }
    else { //Pokud smer = j
        if (($os == "yes") && ($sp == "no")){
            if (($both == "yes") && ($there == "yes")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND S.vlak_id=V.id AND  V.typ='os'";
            }
            else if (($both == "no") && ($there == "yes")) {
                $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND P.smer_id=M.id AND M.smer='j' AND S.vlak_id=V.id AND  V.typ='os'";
            }
            else if (($both == "yes") && ($there == "no")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND P.smer_id=M.id AND M.smer='s' AND S.vlak_id=V.id AND  V.typ='os'";
            }
        }
        else if (($sp == "yes") && ($os == "no")){
            if (($both == "yes") && ($there == "yes")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND S.vlak_id=V.id AND V.typ='sp'";
            }
            else if (($both == "no") && ($there == "yes")) {
                $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND P.smer_id=M.id AND M.smer='j' AND S.vlak_id=V.id AND V.typ='sp'";
            }
            else if (($both == "yes") && ($there == "no")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, V.typ, P.smer_id FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND P.smer_id=M.id AND M.smer='s' AND S.vlak_id=V.id AND V.typ='sp'";
            }
        }
        else if (($sp == "yes") && ($os == "yes")){
            if (($both == "yes") && ($there == "yes")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, P.smer_id, V.typ FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND S.vlak_id=V.id";
            }
            else if (($both == "no") && ($there == "yes")) {
                $qry = "SELECT DISTINCT S.id, S.vlak_id, P.smer_id, V.typ FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND P.smer_id=M.id AND M.smer='j' AND S.vlak_id=V.id";
            }
            else if (($both == "yes") && ($there == "no")){
                $qry = "SELECT DISTINCT S.id, S.vlak_id, P.smer_id, V.typ FROM spoje S JOIN prijezdy P JOIN smer M JOIN vlak V WHERE P.stanice_id='$key' AND P.id=S.prijezd_id AND S.provozni_den='2017-03-13' AND P.smer_id=M.id AND M.smer='s' AND S.vlak_id=V.id";
            }
        }
    }
    //echo "Dotaz: " . $qry . "<br>";
    $result = mysqli_query($con,$qry);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            //echo "vlak id: " . $row["vlak_id"] . " " .  "id: " . $row["id"]. " " . "cislo spoje: " . $row["cislo"] . "<br>";
            $trains[intval($row["vlak_id"])][intval($row["cislo_spoje"])][] = intval($row["id"]); //vlak => id => cislo spoje => spoje

            //Ulozim id os vlaku pro modrou barvu
            if (($row["typ"] == "os")&&(!in_array(intval($row["vlak_id"]), $os_train_color))){
                $os_train_color[] = intval($row["vlak_id"]); //Pole os vlaku pro barvu (modrou)
            }

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
//print_r($trains);
//echo "<br>";

//Ulozim ke kazdemu vlaku jeho cislo
foreach ($trains as $key => $value){
    $qry = "SELECT DISTINCT cislo FROM vlak WHERE id='$key'";
    //echo "Dotaz: " . $qry . "<br>";
    $result = mysqli_query($con,$qry);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            //echo "id: " . $key . " " .  "cislo vlaku: " . $row["cislo"] . "<br>";
            $trains[$key]["cislo"] = intval($row["cislo"]);
        }
    } else {
        echo "0 results F";
    }
}
//print_r($trains);
//echo "<br>";

/*
 * Vytvoreni JSON dat
*/
$json_data = array();
//Vytovorim pole pro cols
$col_member = array();
$style_membr = array();
$tooltip_member = array();
$num_of_trains = array();
//Vlozim col pro datum - vzdy
$col_member["id"] = "";
$col_member["label"] = "Time";
$col_member["pattern"] = "";
$col_member["type"] = "datetime";
$json_data["cols"][] = $col_member;

//Podle poctu vlaku vlozim col pro spoje
foreach ($trains as $key => $value){

    $num_of_trains[] = $value["cislo"]; //Ulozim cisla vlaku pro tooltip

    $col_member["id"] = "";
    $col_member["label"] = "Vlak {$value["cislo"]}";
    $col_member["pattern"] = "";
    $col_member["type"] = "number";
    $json_data["cols"][] = $col_member;

    //Data slouzici pro zmenu barvy
    $style_member["id"] = "";
    $style_member["role"] = "style";
    $style_member["type"] = "string";
    $json_data["cols"][] = $style_member;

    //Data slouzici jako tooltip
    $tooltip_member["id"] = "";
    $tooltip_member["role"] = "tooltip";
    $tooltip_member["type"] = "string";
    $tooltip_member["p"]["html"] = "true";
    $json_data["cols"][] = $tooltip_member;
}

//Vytvorim pole pro rows
$row_member = array();
$row_submember = array(); //pro c [ ]
$submember_data = array(); //Pole pro casy vlaku v jednotlivych stanicich

//print_r($os_train_color);
//echo "<br><br>";
//print_r($trains);
//echo "<br><br>";

$blue_trains = array(); //Pole obsahujici poradi spoju urcene $connect_cnt pro barvu (modrou)
$opposite_trains = array(); //Pole obsahujici poradi spoju s opacnym smerem pro upravy tooltipu
//Vytvorim json data pro vsechny vlaky
$connect_cnt = 0; //Pocitadlo spoju (jeden vlak i vice spoju)
$is_in = false;
foreach ($trains as $train_id => $train_inf){

    //Pokud se jedna o vlak, ktery ma byt modre (muze mit vic spoju)
    if (in_array($train_id, $os_train_color)){
        $is_in = true;
    }
    else{
        $is_in = false;
    }

    foreach ($train_inf as $conn_num => $con_arr){
        if (is_numeric($conn_num)){ //abych nevybral [cislo]
            $connect_cnt++;

            if ($is_in){
                $blue_trains[] = $connect_cnt;
            }

            //Pokud se jedna o vlak co je v $id_opposite, jeho poradi priradim do $opposite_trains
            if (in_array($train_id, $id_opposite)){
                $opposite_trains[] = $connect_cnt;
            }

            //echo "--cislo_spoje:  " . $conn_num;
            //echo "<br>";
            foreach ($con_arr as $key => $value){
                //echo "------------spoj_id:    " . $value;
                //echo "<br>";

                //Zjistim cas
                $qry="SELECT DISTINCT P.prijezd, P.odjezd, P.id FROM prijezdy P JOIN spoje S WHERE S.id='$value' AND S.prijezd_id=P.id";
                //echo "Dotaz: " . $qry . "<br>";
                $result = mysqli_query($con,$qry);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        //echo "id: " . $value . " " . "prijezd: " . $row["prijezd"] .  "odjezd: " . $row["odjezd"] . "<br>";
                        $arr = $row["prijezd"];
                        $dep = $row["odjezd"];

                        if (strlen($arr) < 1) {
                            //echo "ted <br>";
                            $arr = "null";
                        }
                        if (strlen($dep) < 1) {
                            //echo "ted <br>";
                            $dep = "null";
                        }

                        $p_id = intval($row["id"]); //id prijezdu pro zjisteni vzdalenosti
                       // echo $time . "<br>";
                    }
                } else {
                    echo "0 results G";
                }

                //zjistim vzdalenost
                $qry="SELECT DISTINCT T.vzdalenost FROM prijezdy P JOIN stanice T WHERE P.id='$p_id' AND P.stanice_id=T.id";
                //echo "Dotaz: " . $qry . "<br>";
                $result = mysqli_query($con,$qry);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        //echo "id: " . $value . " " .  "odjezd: " . $row["odjezd"] . "<br>";
                        $vzdalenost = intval($row["vzdalenost"]);
                        //echo  "vzdalenost: " . $vzdalenost . "<br>";
                    }
                } else {
                    echo "0 results H";
                }

                //Pokud vykresluju i zpatecni, prohodim casy prijezd/odjezd kvuli vykreslovani
                if (in_array($train_id, $id_opposite)){
                    $submember_data[$vzdalenost]["prijezd"][] = $dep;
                    $submember_data[$vzdalenost]["odjezd"][] = $arr;
                }
                else{
                    $submember_data[$vzdalenost]["prijezd"][] = $arr;
                    $submember_data[$vzdalenost]["odjezd"][] = $dep;
                }
            }
        }
    }
}

//print_r($blue_trains);
//echo "<br><br>";
//print_r($submember_data);
//echo "<br><br>";

//Pomoci submember_data obsahujici casy vlaku v jednotlivych stanicich vytvorim json data
//Pres vsechny stanice : [casy]
foreach ($submember_data as $station => $times){
    //Pres vsechny [vlak] : [prijezd/odjezd]
    foreach ($times as $train_num => $time){
        //Pres vsechny [prijezd/odjezd] : cas
        foreach ($time as $key => $value){

            //echo "---------------" . $value . "---------------" . "<br>";

            if ($value == "null"){
                //echo "Ted <br>";
                continue;
            }
            else {
                $date_time = $date . "," . $value;
                $date_time = str_replace("-",",",$date_time);
                $date_time = str_replace(":",",",$date_time);
                $date_time = "Date(" . $date_time . ")";

                $row_submember["v"] = $date_time;
                $row_member["c"][0] = $row_submember;

                $last_i = 0; //Pocitadlo pro porovnavani spravnych casu
                $property_cnt = 0; //Pocitadlo pro urceni vlatnosti - barva, tooltip

                for ($i = 1; $i <= $connect_cnt*3; $i++){
                    $last_i++;
                    $property_cnt++;

                   // echo "last_i: " . $last_i . "<br>" . "i: " . $i . "<br>";
                   // echo "porovnavam: " . $time[$key] . " s: " . $time[$i-$last_i] . "<br>";

                    if ($time[$key] == $time[$i-$last_i]){
                        $row_submember["v"] = $station;
                        $row_member["c"][$i] = $row_submember;
                    }
                    else {
                        $row_submember["v"] = "null";
                        $row_member["c"][$i] = $row_submember;
                    }

                    //Pokud se spracovava OS vlak z $blue_trains, je modry, jinak cerveny
                    if (in_array($property_cnt, $blue_trains)){
                        $i++;
                        //echo "modry: " . $i . "<br>";
                        $row_submember["v"] = "color: #4885ed"; //modra
                        $row_member["c"][$i] = $row_submember;
                    }
                    else {
                        $i++;
                        //echo "cerveny: " . $i . "<br>";
                        $row_submember["v"] = "color: #db3236;stroke-width: 5;"; //cervena
                        $row_member["c"][$i] = $row_submember;
                    }

                    //Pokud se jedna o zobrazeni opacnych spoju, musim upravit Prijezd/Odjezd
                    if (in_array($property_cnt, $opposite_trains)){
                         $row_submember["v"] = "<p><strong>Vlak číslo: {$num_of_trains[$i-($last_i+1)]}</strong></p><p>Příjezd:  {$times["odjezd"][$i-($last_i+1)]}<p><p>Odjezd: {$times["prijezd"][$i-($last_i+1)]}</p>";
                    }
                    else{
                         $row_submember["v"] = "<p><strong>Vlak číslo: {$num_of_trains[$i-($last_i+1)]}</strong></p><p>Příjezd: {$times["prijezd"][$i-($last_i+1)]}</p><p>Odjezd:  {$times["odjezd"][$i-($last_i+1)]}<p>";
                    }
                    $i++;
                    $last_i++;
                    $row_member["c"][$i] = $row_submember;

                    //Pokud pocitadlo dosahne maxima, vynuluju
                    if ($property_cnt == $connect_cnt){
                        $property_cnt = 0;
                    }
                }
            }
            $json_data["rows"][] = $row_member;
        }
    }
}

//print json_encode($row_member);
//print json_encode($row_member);
//echo "<br><br>";
//print json_encode($json_data);
//([1]=>Array([1]=>Array([0]=>1,[1]=>4,[2]=>6,[3]=>7),[cislo]=>4119),[2]=>Array([2]=>Array([0]=>8,[1]=>9,[2]=>10,[3]=>11,),[cislo]=>1717))
$json_data = json_encode($json_data, true);
mysqli_close($con);
echo $json_data;

?>
