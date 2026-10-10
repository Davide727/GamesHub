<?php
/*
 Verifica se un utente è loggato tramite le variabili di sessione e, in caso affermativo,
 ne verifica l'esistenza nel database; se non esiste, tenta di inserire un nuovo record.
 La pagina include metadati HTML di base, collegamenti a fogli di stile CSS (Bootstrap e personalizzato),
 la struttura per le griglie di gioco del giocatore e del computer (che verranno popolate via JavaScript),
 un'area per i messaggi di stato del gioco e i pulsanti di interazione (rotazione nave e nuova partita).
 Contiene anche una sezione per visualizzare le statistiche del giocatore loggato o un messaggio per gli utenti non loggati.
 Il comportamento dinamico del gioco è gestito tramite il file JavaScript 'battaglianavale_script.js' incluso alla fine del body.
*/
session_start();
include 'connessione.php';

if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
  $id = $_SESSION['user_id'];
  $username = $_SESSION['username'];
  $sql = "SELECT * FROM battaglia_navale_utenti WHERE id = $id";
  $result = $conn->query($sql);
  if ($result->num_rows == 0) {
    $sql = "INSERT INTO battaglia_navale_utenti (id, username) VALUES ($id, '$username')";
    if ($conn->query($sql) === false) {
      echo "Errore nell'inserimento dell'utente: " . $conn->error;
    }
  }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Battaglia Navale</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="battaglianavale_style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body>

<a href="games.php" class="exit-btn">Esci</a>

<div class="container">
  <h1 class="text-center">Battaglia Navale</h1>

  <!-- Sezione di gioco -->
  <div class="grids">
    <div>
      <h2>Griglia Giocatore</h2>
      <div id="player-grid" class="board"></div>
    </div>
    <div>
      <h2>Griglia Computer</h2>
      <div id="computer-grid" class="board"></div>
    </div>
  </div>

  <!-- Messaggi di stato e pulsanti -->
  <div id="game-status">
    <div id="message" class="message"></div>
    <div id="sunk-message" class="sunk-alert"></div>
  </div>

  <button id="rotate">Orientamento: Orizzontale</button>
  <button id="new-game" class="hidden">Nuova Partita</button>

    <!-- Riquadro statistiche sotto i pulsanti -->
    <div id="stats-container">
      <?php if(isset($_SESSION['user_id'])): ?>
        <button onclick="window.location.href='statistiche.php'" class="stats-btn">
          Vedi Statistiche Complete
        </button>
      <?php else: ?>
        <p>Non sei loggato. <br>Effettua il <strong>login</strong> per tenere traccia dei tuoi punteggi!</p>
        <a href="login.html" class="btn">Vai alla pagina di Login</a>
      <?php endif; ?>
    </div>
    
  </div>
</div>

<script src="battaglianavale_script.js"></script>
</body>
</html>
