<?php
/*
 Riceve tramite POST username ed email e verifica se esiste un utente con tali credenziali nel database.
 Restituisce una risposta JSON indicando l'esito della verifica.
*/
require 'connessione.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];

    $stmt = $conn->prepare("SELECT id FROM utenti WHERE username = ? AND email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Username ed email non corrispondono.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Richiesta non valida.']);
}
$conn->close();
?>