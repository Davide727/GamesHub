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

    $stmt = $conn->prepare("SELECT utente_id_richiedente FROM amicizie WHERE id = ? AND utente_id_ricevente = ? AND stato = 'inviata'");
    $stmt->bind_param("ii", $richiesta_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $stmt = $conn->prepare("UPDATE amicizie SET stato = 'accettata', data_accettazione = NOW() WHERE id = ?");
        $stmt->bind_param("i", $richiesta_id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Richiesta di amicizia accettata con successo!";
        } else {
            $_SESSION['error_message'] = "Errore durante l'accettazione della richiesta.";
        }
    } else {
        $_SESSION['error_message'] = "Richiesta di amicizia non valida o non trovata.";
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error_message'] = "ID richiesta non valido.";
}

header("Location: richieste_ricevute.php");
exit();
?>