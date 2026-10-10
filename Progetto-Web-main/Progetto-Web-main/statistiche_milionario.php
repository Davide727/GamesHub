<?php
/*
 Se l'utente è loggato, recupera il suo ID dalla sessione ed esegue una query al database per ottenere le sue statistiche dalla tabella 'milionario_utenti'.
 Le statistiche recuperate includono username, numero di partite giocate e il massimo guadagno raggiunto.
 Utilizza la sessione per verificare l'autenticazione dell'utente. Include il file 'connessione.php' per stabilire una connessione al database.
 Esegue una query SQL per selezionare le statistiche dell'utente loggato. Recupera e visualizza i dati delle statistiche all'interno del codice HTML.
*/
session_start();
include 'connessione.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: milionario.php");
    exit();
}

$id = $_SESSION['user_id'];
$sql = "SELECT * FROM milionario_utenti WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Statistiche non trovate.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Statistiche Chi Vuol Essere Milionario</title>
    <link rel="stylesheet" href="statistiche.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body>
    <h1>Statistiche Chi Vuol Essere Milionario</h1>
    <div class="stats-box">
        <p>Username: <?php echo $row['username']; ?></p>
        <p>Partite giocate: <?php echo $row['partite_giocate']; ?></p>
        <p>Massimo vinto: <?php echo $row['massimo_vinto']; ?>€</p>
    </div>
    <div class="button-container">
        <a href="milionario.php">Torna a Chi Vuol Essere Milionario</a>
    </div>
</body>
</html>