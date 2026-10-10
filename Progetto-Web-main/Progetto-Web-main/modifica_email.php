<?php
/*
 Aggiorna l'email dell'utente autenticato.
*/
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include 'connessione.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if (empty($email)) {
        echo json_encode(['success' => false, 'error' => "L'email non può essere vuota."]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => "Email non valida."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM utenti WHERE email = ? AND id != ?");
    $stmt->bind_param("si", $email, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => "Email già esistente."]);
        exit;
    }
    $stmt->close();

    $stmt = $conn->prepare("UPDATE utenti SET email = ? WHERE id = ?");
    $stmt->bind_param("si", $email, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Email aggiornata con successo!"]);
    } else {
        echo json_encode(['success' => false, 'error' => "Errore durante l'aggiornamento dell'email: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => "Richiesta non valida."]);
}
?>