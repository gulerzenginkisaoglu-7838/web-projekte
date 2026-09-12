<?php
$host = "sqlXXX.infinityfree.com"; // Euer Host
$user = "if0_XXXXXX";            // Euer DB User
$pass = "EuerPasswort";          // Euer DB Passwort
$dbname = "if0_XXXXXX_db";       // Euer DB Name

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) { die("Verbindung fehlgeschlagen"); }
?>
