<?php
$kasutaja = "leena";
$dbserver = "localhost";
$andmebaas = "restoranid";
$pw = "Password123";

$yhendus = mysqli_connect($dbserver, $kasutaja, $pw, $andmebaas);
if(!$yhendus){
    // echo "jama majas";
    die("Ei saa ühendust!");
} 

?>