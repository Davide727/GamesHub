<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $amico_id = intval($_GET['id']);

    require 'connessione.php';

    $stmt = $conn->prepare("DELETE FROM amicizie
                            WHERE stato = 'accettata'
                            AND ((utente_id_richiedente = ? AND utente_id_ricevente = ?)
                                 OR (utente_id_richiedente = ? AND utente_id_ricevente = ?))");
    $stmt->bind_param("iiii", $user_id, $amico_id, $amico_id, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Amico rimosso con successo.";
    } else {
        $_SESSION['error_message'] = "Errore durante la rimozione dell'amico.";
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error_message'] = "ID amico non valido.";
}

header("Location: lista_amici.php");
exit();
?>