<?php
include('db.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pw = $_POST['password'];
    $hashed_pw = password_hash($pw, PASSWORD_DEFAULT); // Sicherer Hash

    $sql = "INSERT INTO users (username, password_hash) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user, $hashed_pw);
    if ($stmt->execute()) { header("Location: index.php"); }
}
?>
<form method="post">
    <input type="text" name="username" placeholder="Nutzername" required>
    <input type="password" name="password" placeholder="Passwort" required>
    <button type="submit">Registrieren</button>
</form>
