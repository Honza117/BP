<?php
// Nacteme hledany retezec do promenne
$q = strtolower($_POST["q"]);
$track = ($_POST["track"]);

//if (!$q) return;
// Pripojime se k databazi
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

if (intval($track) == 0){
  $rows = -1;
  return $rows;
}

$qry = "SELECT DISTINCT S.jmeno FROM stanice S JOIN trate T WHERE UPPER(S.jmeno) LIKE UPPER('%$q%') AND S.trat_id=T.id AND T.cislo='$track'";

$result = mysqli_query($con,$qry);

$rows = array();
// Do pole ulozime a vypiseme vysledek
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        //echo "id: " . $key . " " .  "cislo vlaku: " . $row["cislo"] . "<br>";
        if ($t == "yes"){
          $rows[] = $row["cislo"];
        }
        else{
          $rows[] = $row["jmeno"];
        }
    }
} else {
      echo "0 results";
}

$rows = json_encode($rows,true);
mysqli_close($con);
print $rows;

?>
