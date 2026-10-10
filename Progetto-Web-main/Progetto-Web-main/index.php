<?php
session_start();

include 'connessione.php';

if (isset($_COOKIE['remember_token']) && !isset($_SESSION['user_id'])) {
    $remember_token = $_COOKIE['remember_token'];

    $stmt = $conn->prepare("SELECT id, username FROM utenti WHERE remember_token = ?");
    $stmt->bind_param("s", $remember_token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username);
        $stmt->fetch();

        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GamesHub - Home</title>

  <meta name="description" content="Benvenuto su GamesHub! Scopri, gioca e divertiti con i migliori giochi online e offline.">
  <meta name="keywords" content="Giochi, Retro, Online, Offline, Arcade, Sapienza, Home">
  <meta name="author" content="Gruppo Sapienza DIAG">
  <meta name="robots" content="index, follow">

  <meta property="og:title" content="GamesHub - Home">
  <meta property="og:description" content="Benvenuto su GamesHub! Scopri, gioca e divertiti con i migliori giochi online e offline.">
  <meta property="og:image" content="http://gameshub.local/immagini/logo2.png">
  <meta property="og:url" content="http://gameshub.local/index.php">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="index.css">
  <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body>

  <?php include 'header.php'; ?>

  <section class="hero">
    <div class="hero-container">
      <img src="immagini/mascot.png" alt="Mascot" class="mascot left">
      <h1 class="title">GamesHub</h1>
      <p class="tagline">Scopri, gioca e divertiti con i migliori giochi online e offline!</p>
      <div class="buttons">
        <?php
          if (isset($_SESSION['user_id'])) {
            echo '<button class="btn multiplayer" onclick="location.href=\'games.php\'">Gioca</button>';
          } else {
            echo '<button class="btn multiplayer" onclick="location.href=\'login.html\'">Login</button>';
            echo '<div class="or">OR</div>';
            echo '<button class="btn singleplayer" onclick="location.href=\'games.php\'">Guest</button>';
          }
        ?>
      </div>
      <img src="immagini/mascot.png" alt="Mascot" class="mascot right">
    </div>
  </section>

  <section class="section-container featured-games">
    <h2 class="section-title title">Giochi del Momento</h2>
    <div class="games-grid">
      <div class="game-card" onclick="window.location.href='milionario.php'">
        <img src="immagini/milionario.png" alt="Milionario">
        <h3>Milionario</h3>
        <p>Metti alla prova la tua conoscenza e vinci il montepremi virtuale in questo quiz avvincente!</p>
      </div>
      <div class="game-card" onclick="window.location.href='battaglianavale.php'">
        <img src="immagini/battaglia_navale.jpg" alt="Battaglia Navale">
        <h3>Battaglia Navale</h3>
        <p>Affonda le navi nemiche in questo classico gioco strategico offline!</p>
      </div>

    </div>
  </section>

  <section class="section-container leaderboard">
    <h2 class="section-title title">Utenti più attivi</h2>
    <table>
      <thead>
        <tr>
          <th>Rank</th>
          <th>Player</th>
          <th>Total Games</th>
        </tr>
      </thead>
      <tbody id="leaderboard-body">
      </tbody>
    </table>
  </section>

  <script src="leaderboard.js"></script>

  <section class="section-container icons">
    <h2 class="section-title title">Seguici</h2>
    <div class="social-login-icons">
      <div class="socialcontainer">
        <a href="https://x.com/diagsapienza" target="_blank" class="icon social-icon-1-1">

          <svg viewBox="0 0 512 512" height="1.7em" xmlns="http://www.w3.org/2000/svg" fill="white">
            <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"></path>
          </svg>
        </a>
        <a href="https://x.com/diagsapienza" target="_blank" class="social-icon-1">

          <svg viewBox="0 0 512 512" height="1.7em" xmlns="http://www.w3.org/2000/svg" fill="white">
            <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"></path>
          </svg>
        </a>
      </div>
  
      <div class="socialcontainer">
        <a href="https://www.instagram.com/diagsapienza/" target="_blank" class="icon social-icon-2-2">

          <svg fill="white" viewBox="0 0 448 512" height="1.5em" xmlns="http://www.w3.org/2000/svg">
              <path
              d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"
            ></path>
          </svg>
        </a>
        <a href="https://www.instagram.com/diagsapienza/" target="_blank" class="social-icon-2">

          <svg fill="white" viewBox="0 0 448 512" height="1.5em" xmlns="http://www.w3.org/2000/svg">
              <path
              d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"
            ></path>
          </svg>
        </a>
      </div>

      <div class="socialcontainer">
        <a href="https://www.facebook.com/DIAG.SAPIENZA/?locale=it_IT" target="_blank" class="icon social-icon-3-3">

          <svg viewBox="0 0 384 512" fill="white" height="1.6em" xmlns="http://www.w3.org/2000/svg">
              <path
              d="M80 299.3V512H196V299.3h86.5l18-97.8H196V166.9c0-51.7 20.3-71.5 72.7-71.5c16.3 0 29.4 .4 37 1.2V7.9C291.4 4 256.4 0 236.2 0C129.3 0 80 50.5 80 159.4v42.1H14v97.8H80z"
            ></path>
          </svg>
        </a>
        <a href="https://www.facebook.com/DIAG.SAPIENZA/?locale=it_IT" target="_blank" class="social-icon-3">
          
          <svg viewBox="0 0 384 512" fill="white" height="1.6em" xmlns="http://www.w3.org/2000/svg">
              <path
              d="M80 299.3V512H196V299.3h86.5l18-97.8H196V166.9c0-51.7 20.3-71.5 72.7-71.5c16.3 0 29.4 .4 37 1.2V7.9C291.4 4 256.4 0 236.2 0C129.3 0 80 50.5 80 159.4v42.1H14v97.8H80z"
            ></path>
          </svg>
        </a>
      </div>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 GamesHub. Tutti i diritti riservati.</p>
  </footer>
</body>
</html>