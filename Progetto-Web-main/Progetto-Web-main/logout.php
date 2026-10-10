<?php
session_start();

include 'connessione.php';

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("UPDATE utenti SET remember_token = NULL WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
}

setcookie('remember_token', '', time() - 3600, '/');

session_destroy();
header('Location: index.php');
exit();
?>