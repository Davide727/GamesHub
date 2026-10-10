<?php
/*
 Aggiorna la password dell'utente autenticato.
*/
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include 'connessione.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];

    if (empty($password)) {
        echo json_encode(['success' => false, 'error' => "La password non può essere vuota."]);
        exit;
    }

    $passwordPattern = '/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/';
    if (!preg_match($passwordPattern, $password)) {
        echo json_encode(['success' => false, 'error' => "La password deve contenere tra 8 e 16 caratteri, almeno una lettera maiuscola e un numero."]);
        exit;
    }

    // Cripta la password prima di salvarla.
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE utenti SET password_ = ? WHERE id = ?");
    $stmt->bind_param("si", $hashedPassword, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => "Password aggiornata con successo!"]);
    } else {
        echo json_encode(['success' => false, 'error' => "Errore durante l'aggiornamento della password: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => "Richiesta non valida."]);
}
?>
