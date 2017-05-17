<?php

/*
 * Obsluha formulare - kontrola
*/
$from = $_POST["from"];
$where = $_POST["where"];
$date = $_POST["date"];
$date = "2017-03-13"; //TESTOVACI HODNOTA

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
        $from_id = intval($row["trat_id"]);
        $from_length = intval($row["vzdalenost"]);  
    }
} else {
    echo "0 results";
}

//Zjisteni ID Kam
$qry = "SELECT trat_id, vzdalenost FROM stanice WHERE jmeno='$where'";
$result = mysqli_query($con,$qry);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $where_id = intval($row["trat_id"]);
        $where_length = intval($row["vzdalenost"]); 
    }
} else {
    echo "0 results";
}

if ($from_length < $where_length){
    $qry = "SELECT jmeno, vzdalenost FROM stanice WHERE trat_id='$from_id' AND vzdalenost BETWEEN $from_length AND $where_length ORDER BY vzdalenost"; //od nejmensi po nejvetsi
}
else {
    $qry = "SELECT jmeno, vzdalenost FROM stanice WHERE trat_id='$from_id' AND vzdalenost BETWEEN $where_length AND $from_length ORDER BY vzdalenost DESC"; //od nejvetsi po nejmensi
}

$result = mysqli_query($con,$qry);
$ticks = array(); 
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        //$ticks[$row["vzdalenost"]] = $row["jmeno"];
        $ticks[] = $row["jmeno"];
        }
} else {
    echo "0 results";
}

/*$data = array();
$data_member = array();

foreach ($ticks as $key => $value){
    $data_member["v"] = $key;
    $data_member["f"] = $value;
    $data[] = $data_member;
}*/

$json_data = json_encode($ticks);
mysqli_close($con);
echo $json_data;
?>