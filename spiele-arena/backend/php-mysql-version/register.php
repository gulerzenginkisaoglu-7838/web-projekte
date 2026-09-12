<?php
include('db.php');
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_pw = $_POST['confirm_password'];

    if ($password !== $confirm_pw) {
        $message = "Fehler: Passwörter stimmen nicht überein!";
    } elseif (strlen($password) < 8) {
        $message = "Fehler: Passwort muss mindestens 8 Zeichen lang sein!";
    } else {
        $hashed_pw = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password_hash) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $hashed_pw);

        try {
            if ($stmt->execute()) {
                header("Location: index.php?reg=success");
            }
        } catch (Exception $e) {
            $message = "Fehler: Benutzername existiert bereits!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Registrierung</title></head>
<body>
    <h1>Konto erstellen</h1>
    <p style="color:red;"><?php echo $message; ?></p>
    <form method="post">
        <input type="text" name="username" placeholder="Benutzername" required><br><br>
        <input type="password" name="password" placeholder="Passwort (min. 8)" required><br><br>
        <input type="password" name="confirm_password" placeholder="Bestaetigen" required><br><br>
        <button type="submit">Registrieren</button>
    </form>
    <a href="index.php">Zurück zum Login</a>
</body>
</html>
