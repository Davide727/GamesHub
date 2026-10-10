<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $richiesta_id = intval($_GET['id']);

    require 'connessione.php';

    $stmt = $conn->prepare("DELETE FROM amicizie WHERE id = ? AND utente_id_ricevente = ? AND stato = 'inviata'");
    $stmt->bind_param("ii", $richiesta_id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Richiesta di amicizia rifiutata ed eliminata.";
    } else {
        $_SESSION['error_message'] = "Errore durante il rifiuto ed eliminazione della richiesta.";
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error_message'] = "ID richiesta non valido.";
}

header("Location: richieste_ricevute.php");
exit();
?>