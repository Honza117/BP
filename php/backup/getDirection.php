<?php

/*
 * Obsluha formulare
*/
$from = $_POST["from"];
$where = $_POST["where"];

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

//Urcim smer osy Y
if ($from_length < $where_length){
    $direct = -1;
}
else{
    $direct = 1;
}

mysqli_close($con);
echo $direct;
?>