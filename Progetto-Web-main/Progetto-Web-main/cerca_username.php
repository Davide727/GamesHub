<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    exit();
}

require 'connessione.php'; 

$ricerca = isset($_GET['query']) ? trim($_GET['query']) : '';
$user_id_attuale = $_SESSION['user_id'];

$matches = [];

if (!empty($ricerca)) {
    // Prepara la query per cercare username che iniziano con la stringa di ricerca e che non siano l'utente attuale
    $stmt = $conn->prepare("SELECT username FROM utenti WHERE username LIKE ? AND id != ? LIMIT 10");
    $param_ricerca = $ricerca . '%'; // Aggiungi '%' per cercare username che iniziano con la stringa
    $stmt->bind_param("si", $param_ricerca, $user_id_attuale);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $matches[] = $row['username'];
    }

    $stmt->close();
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($matches);
?>