<?php
/*
 Aggiorna il nome utente nella tabella utenti ed usando una transizione, aggiorna le tabelle 'utenti', 'battaglia_navale_utenti',
 'milionario_utenti' e 'tris_utenti'.
*/
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

include 'connessione.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];

    if (empty($username)) {
        echo json_encode(['success' => false, 'error' => "Il nome utente non può essere vuoto."]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id FROM utenti WHERE username = ? AND id != ?");
    $stmt->bind_param("si", $username, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => "Nome utente già esistente."]);
        exit;
    }
    $stmt->close();

    $conn->begin_transaction(); // Inizia una transazione per garantire l'integrità dei dati

    try {
        // Aggiorna la tabella utenti
        $stmtUtenti = $conn->prepare("UPDATE utenti SET username = ? WHERE id = ?");
        $stmtUtenti->bind_param("si", $username, $_SESSION['user_id']);
        $stmtUtenti->execute();
        $stmtUtenti->close();

        // Aggiorna la tabella battaglia_navale_utenti
        $stmtBattagliaNavale = $conn->prepare("UPDATE battaglia_navale_utenti SET username = ? WHERE id = ?");
        $stmtBattagliaNavale->bind_param("si", $username, $_SESSION['user_id']);
        $stmtBattagliaNavale->execute();
        $stmtBattagliaNavale->close();

        // Aggiorna la tabella milionario_utenti
        $stmtMilionario = $conn->prepare("UPDATE milionario_utenti SET username = ? WHERE id = ?");
        $stmtMilionario->bind_param("si", $username, $_SESSION['user_id']);
        $stmtMilionario->execute();
        $stmtMilionario->close();

        // Aggiorna la tabella tris_utenti
        $stmtTris = $conn->prepare("UPDATE tris_utenti SET username = ? WHERE id = ?");
        $stmtTris->bind_param("si", $username, $_SESSION['user_id']);
        $stmtTris->execute();
        $stmtTris->close();

        $conn->commit(); // Se tutte le query hanno successo, commit della transazione
        echo json_encode(['success' => true, 'message' => "Nome utente aggiornato con successo in tutte le tabelle!"]);

    } catch (mysqli_sql_exception $exception) {
        $conn->rollback(); // In caso di errore, rollback della transazione
        echo json_encode(['success' => false, 'error' => "Errore durante l'aggiornamento del nome utente: " . $exception->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => "Richiesta non valida."]);
}
?>