<?php
/*
 La pagina include metadati SEO, collegamenti a fogli di stile CSS (Bootstrap e personalizzati),
 una sezione "Chi Siamo" con un titolo e una breve descrizione, una sezione sul
 "Dipartimento DIAG" con un'immagine, informazioni testuali e un'integrazione di una mappa
 (tramite un iframe). Include anche un footer statico con informazioni sul copyright.
*/
session_start();
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>About - GamesHub</title>

  <meta name="description" content="Scopri chi siamo: un gruppo di studenti dell'Università La Sapienza con la passione per i retro game e lo sviluppo web.">
  <meta name="keywords" content="About, RetroGame, Sapienza, DIAG, progetto web, chi siamo">
  <meta name="author" content="Gruppo Sapienza DIAG">
  <meta name="robots" content="index, follow">

  <!-- Social / Open Graph -->
  <meta property="og:title" content="GamesHub - About">
  <meta property="og:description" content="Scopri chi siamo: un gruppo di studenti dell'Università La Sapienza con la passione per i retro game e lo sviluppo web.">
  <meta property="og:image" content="https://gameshub.local/immagini/Diag.png">
  <meta property="og:url" content="https://gameshub.local/about.php">

  <!-- BOOTSTRAP CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- CSS Personalizzati -->
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="about.css">
  <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body>

  <!-- Navbar -->
  <?php include 'header.php'; ?>
  <main>
    
    <!-- Sezione "Chi Siamo" -->
    <section class="about-hero">
      <div class="about-hero-container">
        <h1 class="about-title">Chi Siamo</h1>
        <p class="about-tagline">
          Siamo un gruppo di studenti di Ingegneria Informatica e Automatica dell'Università La Sapienza di Roma, 
          appassionati di tecnologia e giochi, che hanno messo a punto questo progetto per condividere la nostra esperienza e creatività.
        </p>
      </div>
    </section>
    
   <!-- Sezione DIPARTIMENTO DIAG -->
    <section class="about-diag">
      <div class="diag-container">
        <div class="diag-image">
          <img src="immagini/Diag.png" alt="Logo DIAG">
        </div>
        <div class="diag-info">
          <h2>DIAG</h2>
          <p>
            Il Dipartimento di Ingegneria Informatica Automatica e Gestionale "Antonio Ruberti" si trova in Via Ariosto, 25, 00185 Roma RM. Tel.: 06 7727 4140. Il dipartimento offre un ambiente di formazione e ricerca all’avanguardia, con un approccio innovativo e una stretta collaborazione tra studenti e docenti.
          </p>
          <div class="map-container">
            <iframe 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2994.5552545003104!2d12.494246515773057!3d41.89332037922157!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x132f61a6f4ed65b9%3A0xd6c2c5af30f3e5c4!2sVia%20Ariosto%2C%2025%2C%2000185%20Roma%20RM!5e0!3m2!1sit!2sus!4v1680228691378!5m2!1sit!2sus" 
              width="100%" 
              height="250" 
              style="border:0;" 
              allowfullscreen="" 
              loading="lazy">
            </iframe>
          </div>
        </div>
      </div>
    </section>
  </main>
  
  <!-- Footer -->
  <footer>
    <p>© 2025 GamesHub. Tutti i diritti riservati.</p>
  </footer>

  <!-- BOOTSTRAP JS BUNDLE -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
