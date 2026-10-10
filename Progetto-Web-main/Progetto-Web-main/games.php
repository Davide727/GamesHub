<?php
/*
 La pagina include metadati HTML SEO, collegamenti a fogli di stile CSS (Bootstrap e personalizzati),
 una sezione per i giochi offline che mostra le card per "Milionario" e "Battaglia Navale",
 ognuna con un'immagine, un titolo sempre visibile e una breve descrizione, e che reindirizzano alle rispettive pagine di gioco tramite JavaScript onclick.
 Presenta anche una sezione per i giochi online, che visualizza una card per il gioco "Tris"
 solo se l'utente è loggato (verificato tramite la variabile di sessione 'user_id').
 Se l'utente non è loggato, viene mostrato un messaggio che lo informa sulla necessità di effettuare il login per accedere ai giochi online.
 Include un footer statico con informazioni sul copyright. La navigazione principale è inclusa tramite il file 'header.php'.
*/
session_start();
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GamesHub - Games</title>

  <meta name="description" content="Scopri tutti i nostri giochi disponibili: titoli offline storici e partite online in stile arcade. Mettiti alla prova!">
  <meta name="keywords" content="Giochi, Offline, Online, Tetris, Pac-Man, Arcades">
  <meta name="author" content="Gruppo Sapienza DIAG">
  <meta name="robots" content="index, follow">

  <!-- Social / Open Graph -->
  <meta property="og:title" content="GamesHub - Giochi">
  <meta property="og:description" content="Sezione dedicata ai migliori giochi, offline e online. Metti alla prova le tue abilità!">
  <meta property="og:image" content="http://gameshub.local/immagini/tetris.jpg"> <!-- Cambia l'immagine in base al gioco da sistemare quando inseriamo i giochi effettivi-->
  <meta property="og:url" content="http://gameshub.local/games.php">

  <!-- BOOTSTRAP CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- CSS Personalizzati -->
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="games.css">
  <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body class="home">

  <!-- Navbar -->
  <?php include 'header.php'; ?>

  <!-- Sezione Giochi Off-line -->
  <section class="offline-games section-container">
    <h2 class="section-title">Giochi Off-line</h2>
    <div class="cards-container">

      <!-- Card: Milionario -->
      <div class="card" onclick="window.location.href='milionario.php'">
        <img src="immagini/milionario.png" alt="Milionario">

        <!-- Titolo sempre visibile -->
        <div class="card-title-overlay">
          <p class="card__title">Milionario</p>
        </div>
        <div class="card__description">
            <p>
            Mettiti alla prova con domande di cultura generale e tenta di vincere il montepremi virtuale rispondendo correttamente a tutte le domande!
            </p>
        </div>
      </div>

      <!-- Card: Battaglia Navale -->
      <div class="card" onclick="window.location.href='battaglianavale.php'">
        <img src="immagini/battaglia_navale.jpg" alt="Battaglia Navale">

        <!-- Titolo sempre visibile -->
        <div class="card-title-overlay">
          <p class="card__title">Battaglia Navale</p>
        </div>
        <div class="card__description">
          <p>
            Sfida il computer in una battaglia strategica per affondare le sue navi prima che lui affondi le tue. Pianifica attentamente ogni mossa!
          </p>
        </div>
      </div>

      <div class="card">
        <!-- Titolo sempre visibile -->
        <div class="card-title-overlay">
            <p class="card__title">Prossimamente</p>
        </div>
        <div class="card__description">
            <p>
            Un nuovo gioco offline sta per arrivare! Resta sintonizzato per scoprire presto tutte le novità e metterti alla prova con nuove sfide.
            </p>
        </div>
      </div>

      <div class="card">
        <!-- Titolo sempre visibile -->
        <div class="card-title-overlay">
            <p class="card__title">Prossimamente</p>
        </div>
        <div class="card__description">
            <p>
            Un nuovo gioco offline sta per arrivare! Resta sintonizzato per scoprire presto tutte le novità e metterti alla prova con nuove sfide.
            </p>
        </div>
      </div>

      <div class="card">
        <!-- Titolo sempre visibile -->
        <div class="card-title-overlay">
            <p class="card__title">Prossimamente</p>
        </div>
        <div class="card__description">
            <p>
            Un nuovo gioco offline sta per arrivare! Resta sintonizzato per scoprire presto tutte le novità e metterti alla prova con nuove sfide.
            </p>
        </div>
      </div>

      <div class="card">
        <!-- Titolo sempre visibile -->
        <div class="card-title-overlay">
            <p class="card__title">Prossimamente</p>
        </div>
        <div class="card__description">
            <p>
            Un nuovo gioco offline sta per arrivare! Resta sintonizzato per scoprire presto tutte le novità e metterti alla prova con nuove sfide.
            </p>
        </div>
      </div>
    </div>
  </section>

    <!-- Sezione Giochi On-line -->
    <section class="online-games section-container">
      <?php if(isset($_SESSION['user_id'])): ?>
      <h2 class="section-title online-title">Giochi On-line</h2>
      <div class="cards-container">

        <!-- Card: Tris (online) -->
        <div class="card" onclick="window.location.href='tris.php'">
          <img src="immagini/tris.jpg" alt="Tris">

          <!-- Titolo sempre visibile -->
          <div class="card-title-overlay">
            <p class="card__title">Tris</p>
          </div>
          <div class="card__description">
            <p>
              Sfida i tuoi amici in questo classico gioco di strategia e metti alla prova la tua capacità di anticipare le mosse avversarie!
            </p>
          </div>
        </div>

        <div class="card">
          <div class="card-title-overlay">
            <p class="card__title">Prossimamente</p>
          </div>
          <div class="card__description">
            <p>
            Un nuovo gioco online sta per arrivare! Resta sintonizzato per scoprire presto tutte le novità e metterti alla prova con nuove sfide.
            </p>
          </div>
        </div>

                <div class="card">
          <div class="card-title-overlay">
            <p class="card__title">Prossimamente</p>
          </div>
          <div class="card__description">
            <p>
            Un nuovo gioco online sta per arrivare! Resta sintonizzato per scoprire presto tutte le novità e metterti alla prova con nuove sfide.
            </p>
          </div>
        </div>

                <div class="card">
          <div class="card-title-overlay">
            <p class="card__title">Prossimamente</p>
          </div>
          <div class="card__description">
            <p>
            Un nuovo gioco online sta per arrivare! Resta sintonizzato per scoprire presto tutte le novità e metterti alla prova con nuove sfide.
            </p>
          </div>
        </div>

                <div class="card">
          <div class="card-title-overlay">
            <p class="card__title">Prossimamente</p>
          </div>
          <div class="card__description">
            <p>
            Un nuovo gioco online sta per arrivare! Resta sintonizzato per scoprire presto tutte le novità e metterti alla prova con nuove sfide.
            </p>
          </div>
        </div>
                <div class="card">
          <div class="card-title-overlay">
            <p class="card__title">Prossimamente</p>
          </div>
          <div class="card__description">
            <p>
            Un nuovo gioco online sta per arrivare! Resta sintonizzato per scoprire presto tutte le novità e metterti alla prova con nuove sfide.
            </p>
          </div>
        </div>
      </div>
      <?php else: ?>
        <p>Per poter avviare i giochi online devi essere loggato</p>
      <?php endif; ?>
    </section>

  <!-- Footer -->
  <footer>
    <p>&copy; 2025 GamesHub. Tutti i diritti riservati.</p>
  </footer>
  
  <!-- BOOTSTRAP JS BUNDLE -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>