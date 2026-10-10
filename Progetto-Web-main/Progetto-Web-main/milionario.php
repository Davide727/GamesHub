<?php
/*
 Gestisce l'inizializzazione della sessione, include il file di connessione al database,
 verifica l'esistenza dell'utente loggato nel database e, se non esiste, lo aggiunge.
 Per gli utenti non loggati, imposta le variabili di sessione come null per gestire
 una sessione ospite. Utilizza la sessione per mantenere lo stato dell'utente (loggato o ospite).
 Si connette al database tramite 'connessione.php'. Esegue query SQL per verificare e/o inserire l'utente nella tabella 'milionario_utenti'.
 Visualizza dinamicamente un box per le statistiche o un avviso di login in base allo stato di autenticazione dell'utente.
*/
session_start();
include 'connessione.php';

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM milionario_utenti WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows == 0) {
        $sql = "INSERT INTO milionario_utenti (id, username, partite_giocate, massimo_vinto) VALUES ($id, '$username', 0, 0)";
        if ($conn->query($sql) === false) {
            echo "Errore nell'inserimento dell'utente: " . $conn->error;
        }
    }
} else {
    $id = null; 
    $username = null; 
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chi Vuol Essere Milionario?</title>
    <link rel="stylesheet" href="milionario.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body>
    <a href="games.php" class="exit-btn">Esci</a>
    <div class="container">
        <h1>Chi Vuol Essere Milionario?</h1>
        <div id="domanda" class="domanda">Qui conterrà la domanda</div>
        <div id="risposte" class="risposte"></div>
        <div id="guadagno" class="guadagno">Guadagno: 0€</div>
        <div id="scaletta" class="scaletta">
            <ul>
                <li data-guadagno="100">100€</li>
                <li data-guadagno="200">200€</li>
                <li data-guadagno="500">500€</li>
                <li data-guadagno="1000">1.000€</li>
                <li data-guadagno="2000">2.000€</li>
                <li data-guadagno="5000">5.000€</li>
                <li data-guadagno="10000">10.000€</li>
                <li data-guadagno="25000">25.000€</li>
                <li data-guadagno="100000">100.000€</li>
                <li data-guadagno="1000000">1.000.000€</li>
            </ul>
        </div>
        <a href="#" id="nuovaPartita" class="bottone">Nuova Partita</a>
        <div id="stats-container">
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="stats-box">
                    <a href="statistiche_milionario.php" class="stats-btn">Vai alla pagina di statistiche</a>
                </div>
            <?php else: ?>
                <div class="guest-warning">
                    <p>Non sei loggato. <br>Effettua il <strong>login</strong> per tenere traccia dei tuoi punteggi!</p>
                    <a href="login.html" class="btn-login">Vai alla pagina di Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="milionario.js"></script>
</body>
</html>