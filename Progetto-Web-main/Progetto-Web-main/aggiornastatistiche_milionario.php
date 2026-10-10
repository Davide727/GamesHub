<?php
/*
 Recupera il guadagno ottenuto dall'utente tramite il metodo POST.
 Verifica se l'utente è autenticato tramite la variabile di sessione 'user_id'.
 Se l'utente è autenticato, esegue una query per selezionare i dati esistenti dell'utente nella tabella 'milionario_utenti'.
 Se l'utente esiste già, incrementa il numero di partite giocate e aggiorna il 'massimo_vinto' se il guadagno attuale è superiore al valore precedentemente registrato.
 Se l'utente non esiste nella tabella, inserisce un nuovo record con l'ID utente, l'username dalla sessione, una partita giocata e il guadagno attuale come massimo vinto.
 Dopo l'aggiornamento o l'inserimento, fornisce un feedback (successo o errore) tramite echo.
 Se l'utente non è autenticato, visualizza un messaggio indicando che l'utente non è loggato.
*/
session_start();
include 'connessione.php';

$guadagno = $_POST['guadagno'];

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $sql = "SELECT * FROM milionario_utenti WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $partite = $row["partite_giocate"] + 1;
        $massimoVinto = max($row["massimo_vinto"], $guadagno);
        $sql = "UPDATE milionario_utenti SET partite_giocate = $partite, massimo_vinto = $massimoVinto WHERE id = $id";
    } else {
        $sql = "INSERT INTO milionario_utenti (id, username, partite_giocate, massimo_vinto) VALUES ($id, '" . $_SESSION['username'] . "', 1, $guadagno)";
    }
    if ($conn->query($sql) === TRUE) {
        echo "Statistiche aggiornate con successo";
    } else {
        echo "Errore nell'aggiornamento delle statistiche: " . $conn->error;
    }
} else {
    echo "Utente non autenticato";
}

$conn->close();
?>