<?php
session_start();
include('db.php');
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: dashboard.php");
    } else {
        $error = "Ungültiger Name oder Passwort!";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Login</title></head>
<body>
    <h1>Game Arena Login</h1>
    <?php if(isset($_GET['reg'])) echo "<p style='color:green;'>Registrierung erfolgreich!</p>"; ?>
    <p style="color:red;"><?php echo $error; ?></p>
    <form method="post">
        <input type="text" name="username" placeholder="Benutzername" required><br><br>
        <input type="password" name="password" placeholder="Passwort" required><br><br>
        <button type="submit">Einloggen</button>
    </form>
    <p>Noch kein Konto? <a href="register.php">Hier registrieren</a></p>
</body>
</html>
