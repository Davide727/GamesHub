<?php
/*
 Recupera l'informazione sulla vittoria o sconfitta dalla richiesta POST.
 Verifica se la variabile di sessione 'user_id' è impostata, indicando che un utente è autenticato.
 Se l'utente è autenticato, controlla il valore della variabile '$vittoria'.
 Se '$vittoria' è 'true', incrementa il numero di vittorie e il numero totale di partite giocate per l'utente nel database.
 Altrimenti (se '$vittoria' non è 'true'), incrementa il numero di sconfitte e il numero totale di partite giocate per l'utente.
 Esegue la query SQL per aggiornare i dati nella tabella 'battaglia_navale_utenti' per l'utente specifico.
 Fornisce un feedback tramite 'echo' indicando se l'aggiornamento delle statistiche è avvenuto con successo o se si è verificato un errore.
 Se l'utente non è autenticato (la variabile di sessione 'user_id' non è impostata), visualizza un messaggio che indica che l'utente non è loggato.
*/
session_start();
include 'connessione.php';

$vittoria = $_POST['vittoria'];

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    if ($vittoria === 'true') {
        $sql = "UPDATE battaglia_navale_utenti SET vittorie = vittorie + 1, numero_partite = numero_partite + 1 WHERE id = $id";
    } else {
        $sql = "UPDATE battaglia_navale_utenti SET sconfitte = sconfitte + 1, numero_partite = numero_partite + 1 WHERE id = $id";
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