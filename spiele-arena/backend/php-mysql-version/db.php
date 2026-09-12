<?php
// Trage hier deine InfinityFree MySQL Daten ein
$host = "sqlXXX.infinityfree.com";
$user = "if0_XXXXXX";
$pass = "EuerPasswort";
$dbname = "if0_XXXXXX_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Datenbankverbindung fehlgeschlagen: " . $conn->connect_error);
}
?>
