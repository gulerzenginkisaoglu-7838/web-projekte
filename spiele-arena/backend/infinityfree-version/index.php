<?php
session_start();
include('db.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pw = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row && password_verify($pw, $row['password_hash'])) {
        $_SESSION['user'] = $row['username'];
        header("Location: dashboard.php");
    } else {
        echo "Falsches Passwort oder Name!";
    }
}
?>
