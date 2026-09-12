<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">
<head><meta charset="UTF-8"><title>Dashboard</title></head>
<body>
    <h1>Hallo, <?php echo $_SESSION['username']; ?>!</h1>
    <p>Willkommen im geschützten Bereich der Game Arena.</p>
    <hr>
    <ul>
        <li>Spiel 1: Coming Soon</li>
        <li>Spiel 2: Coming Soon</li>
    </ul>
    <a href="logout.php">Abmelden</a>
</body>
</html>
