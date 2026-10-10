<?php
/*
 Se l'utente è loggato, recupera l'ID utente dalla sessione ed esegue una query SQLper selezionare le informazioni dalla tabella 'battaglia_navale_utenti'.
 Le informazioni recuperate includono username, numero di partite giocate, numero di
 vittorie e numero di sconfitte. Utilizza la sessione per gestire l'autenticazione dell'utente.
 Include il file 'connessione.php' per stabilire una connessione al database.
 Esegue una query SQL per selezionare i dati dell'utente loggato dalla tabella battaglia_navale_utenti'.
 Recupera i risultati della query e li visualizza all'interno del codice HTML.
*/
session_start();
include 'connessione.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: games.php");
    exit();
}

$id = $_SESSION['user_id'];
$sql = "SELECT * FROM battaglia_navale_utenti WHERE id = $id";
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
    <title>Statistiche Battaglia Navale</title>
    <link rel="stylesheet" type="text/css" href="statistiche.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body>
    <h1>Statistiche Battaglia Navale</h1>
    <div class="stats-box">
        <p>Username: <?php echo $row['username']; ?></p>
        <p>Partite giocate: <?php echo $row['numero_partite']; ?></p>
        <p>Vittorie: <?php echo $row['vittorie']; ?></p>
        <p>Sconfitte: <?php echo $row['sconfitte']; ?></p>
    </div>
    <div class="button-container">
        <a href="battaglianavale.php">Torna a Battaglia Navale</a>
    </div>
</body>
</html>