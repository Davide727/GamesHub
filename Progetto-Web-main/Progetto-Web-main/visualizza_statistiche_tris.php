<?php
/*
 Se l'utente è loggato, recupera l'ID utente dalla sessione ed esegue una query SQL per selezionare le statistiche del Tris dell'utente dalla tabella 'tris_utenti'.
 Le statistiche recuperate includono il numero di partite giocate, vinte, perse e pareggiate.
 Se la query ha successo e viene trovata una riga per l'utente, le statistiche vengono
 memorizzate in variabili PHP. Se non viene trovata alcuna riga, le variabili delle statistiche vengono inizializzate a 0. Infine, chiude la connessione al database.
*/
session_start();
include 'connessione.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$sql = "SELECT partite_giocate, partite_vinte, partite_perse, partite_pareggiate
        FROM tris_utenti WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $partite_giocate = $row['partite_giocate'];
    $partite_vinte = $row['partite_vinte'];
    $partite_perse = $row['partite_perse'];
    $partite_pareggiate = $row['partite_pareggiate'];
} else {
    $partite_giocate = 0;
    $partite_vinte = 0;
    $partite_perse = 0;
    $partite_pareggiate = 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Statistiche Tris</title>
    <link rel="stylesheet" href="tris.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <h1>Statistiche Tris</h1>
        <p>Username: <?php echo htmlspecialchars($username); ?></p>
        <p>Partite Giocate: <?php echo $partite_giocate; ?></p>
        <p>Partite Vinte: <?php echo $partite_vinte; ?></p>
        <p>Partite Perse: <?php echo $partite_perse; ?></p>
        <p>Partite Pareggiate: <?php echo $partite_pareggiate; ?></p>
        <a href="tris.php" class="stats-btn">Torna alla Scelta</a>
    </div>
</body>
</html>