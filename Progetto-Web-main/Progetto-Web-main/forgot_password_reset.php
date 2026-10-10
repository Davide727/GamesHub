<?php
/*
 Riceve tramite POST l'username e la nuova password, cripta la nuova password e la aggiorna nel database per l'utente specificato.
 Restituisce una risposta JSON indicando l'esito dell'aggiornamento.
*/
require 'connessione.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $new_password = $_POST["new_password"];
  
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE utenti SET password_ = ? WHERE username = ?");
    $stmt->bind_param("ss", $hashed_password, $username);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Impossibile aggiornare la password.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Richiesta non valida.']);
}
$conn->close();
?>