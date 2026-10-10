<?php
/*
  Riceve richieste AJAX dal frontend e procede con la cancellazione di tutte le informazioni relative all'utente in questione su diverse tabelle del database.
*/
session_start();

header('Content-Type: application/json'); 

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = "Accesso negato. Utente non autenticato.";
    echo json_encode($response);
    exit();
}

$user_id_to_delete = $_SESSION['user_id']; 

require 'connessione.php'; 

$conn->begin_transaction();

try {

    $stmt = $conn->prepare("DELETE FROM amicizie WHERE utente_id_richiedente = ? OR utente_id_ricevente = ?");
    $stmt->bind_param("ii", $user_id_to_delete, $user_id_to_delete);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM messaggi WHERE mittente_id = ? OR destinatario_id = ?");
    $stmt->bind_param("ii", $user_id_to_delete, $user_id_to_delete);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM tris_partite WHERE giocatore1_id = ? OR giocatore2_id = ?");
    $stmt->bind_param("ii", $user_id_to_delete, $user_id_to_delete);
    $stmt->execute();
    $stmt->close();

    $tables_to_delete = [
        'tris_utenti',
        'milionario_utenti',
        'battaglia_navale_utenti'
    ];

    foreach ($tables_to_delete as $table) {
        $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param("i", $user_id_to_delete);
        $stmt->execute();
        $stmt->close();
    }

    $stmt = $conn->prepare("DELETE FROM utenti WHERE id = ?");
    $stmt->bind_param("i", $user_id_to_delete);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    session_destroy(); 
    $response['success'] = true;
    $response['message'] = "Account eliminato con successo. Sarai reindirizzato alla pagina principale.";

} catch (mysqli_sql_exception $e) {
    $conn->rollback();
    $response['message'] = "Errore durante l'eliminazione dell'account: " . $e->getMessage();
    error_log("Errore eliminazione account ID $user_id_to_delete: " . $e->getMessage()); // Logga l'errore
} finally {
    $conn->close();
}

echo json_encode($response);
?>