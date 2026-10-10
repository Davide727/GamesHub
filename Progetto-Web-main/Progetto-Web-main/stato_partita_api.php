<?php
session_start();
include 'connessione.php';

if (!isset($_GET['partita_id'])) {
    echo json_encode(['success' => false, 'error' => 'ID partita mancante']);
    exit();
}

$partita_id = intval($_GET['partita_id']);
$sql = "SELECT tabella, turno, giocatore1_id, giocatore2_id, vincitore FROM tris_partite WHERE id = $partita_id";
$result = $conn->query($sql);

if ($result && $result->num_rows === 1) {
    $row = $result->fetch_assoc();
    echo json_encode([
        'success' => true,
        'tabella' => $row['tabella'],
        'turno' => $row['turno'],
        'giocatore1_id' => $row['giocatore1_id'],
        'giocatore2_id' => $row['giocatore2_id'],
        'vincitore' => $row['vincitore']
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Partita non trovata']);
}

$conn->close();
?>
