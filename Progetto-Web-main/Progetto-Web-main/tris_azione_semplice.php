<?php
session_start();
include 'connessione.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['partita_id']) || !isset($_POST['tabella']) || !isset($_POST['turno']) || !isset($_POST['vincitore'])) {
    echo "Accesso non valido.";
    exit();
}

$partita_id = $_POST['partita_id'];
$tabella = $_POST['tabella'];
$turno = $_POST['turno'];
$vincitore = $_POST['vincitore'];
$user_id_session = $_SESSION['user_id'];

function aggiornaStatistiche($conn, $user_id, $vincitore_partita, $giocatore1_id, $giocatore2_id) {
    $sql_update_generale = "UPDATE tris_utenti SET partite_giocate = partite_giocate + 1 WHERE id = $user_id";
    $conn->query($sql_update_generale);

    if ($vincitore_partita == 1 && $user_id == $giocatore1_id) {
        $sql_vittoria = "UPDATE tris_utenti SET partite_vinte = partite_vinte + 1 WHERE id = $user_id";
        $conn->query($sql_vittoria);
    } elseif ($vincitore_partita == 2 && $user_id == $giocatore2_id) {
        $sql_vittoria = "UPDATE tris_utenti SET partite_vinte = partite_vinte + 1 WHERE id = $user_id";
        $conn->query($sql_vittoria);
    } elseif ($vincitore_partita != 0 && $vincitore_partita != 3) { 
        if (($vincitore_partita == 1 && $user_id == $giocatore2_id) || ($vincitore_partita == 2 && $user_id == $giocatore1_id)) {
            $sql_sconfitta = "UPDATE tris_utenti SET partite_perse = partite_perse + 1 WHERE id = $user_id";
            $conn->query($sql_sconfitta);
        }
    } elseif ($vincitore_partita == 3) {
        $sql_pareggio = "UPDATE tris_utenti SET partite_pareggiate = partite_pareggiate + 1 WHERE id = $user_id";
        $conn->query($sql_pareggio);
    }
}

$sql_aggiorna_partita = "UPDATE tris_partite SET tabella = '$tabella', turno = $turno, vincitore = $vincitore";

if ($vincitore != 0) {
    $data_fine = date('Y-m-d H:i:s');
    $sql_aggiorna_partita .= ", data_fine = '$data_fine'";
}

$sql_aggiorna_partita .= " WHERE id = $partita_id"; 

if ($conn->query($sql_aggiorna_partita) === FALSE) {
    echo "Errore nell'aggiornamento della partita: " . $conn->error;
}

if ($vincitore != 0) {
    $sql_get_giocatori = "SELECT giocatore1_id, giocatore2_id FROM tris_partite WHERE id = $partita_id";
    $result_giocatori = $conn->query($sql_get_giocatori);

    if ($result_giocatori && $result_giocatori->num_rows == 1) {
        $row_giocatori = $result_giocatori->fetch_assoc();
        $giocatore1_id = $row_giocatori['giocatore1_id'];
        $giocatore2_id = $row_giocatori['giocatore2_id'];

        aggiornaStatistiche($conn, $giocatore1_id, $vincitore, $giocatore1_id, $giocatore2_id);

        aggiornaStatistiche($conn, $giocatore2_id, $vincitore, $giocatore1_id, $giocatore2_id);
    }
}

$conn->close();
?>