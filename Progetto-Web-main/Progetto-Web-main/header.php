<header>
    <nav class="navbar">
      <div class="logo">
        <a href="index.php">
          <img src="immagini/logo2.png" alt="GamesHub Logo" class="logo-img">
        </a>
      </div>

      <input type="checkbox" id="menu-toggle" />

      <label for="menu-toggle" class="burger">
        <span></span>
        <span></span>
        <span></span>
      </label>
      
      <div class="nav-links">
        <?php
          if (isset($_SESSION['user_id'])) {
              echo '<a href="index.php">Home</a>';
              echo '<a href="games.php">Games</a>';
              echo '<a href="about.php">About</a>';
              echo '
              <div class="dropdown">
                <!-- Bottone che mostra il nome utente e apre/chiude il menu a tendina -->
                <button class="dropdown-toggle">
                  <svg class="user-icon" viewBox="0 0 16 16">
                    <path d="m1.5 13v1a.5.5 0 0 0 .3379.4731 18.9718 18.9718 0 0 0 6.1621 1.0269 18.9629 18.9629 0 0 0 6.1621-1.0269.5.5 0 0 0 .3379-.4731v-1a6.5083 6.5083 0 0 0 -4.461-6.1676 3.5 3.5 0 1 0 -4.078 0 6.5083 6.5083 0 0 0 -4.461 6.1676zm4-9a2.5 2.5 0 1 1 2.5 2.5 2.5026 2.5026 0 0 1 -2.5-2.5zm2.5 3.5a5.5066 5.5066 0 0 1 5.5 5.5v.6392a18.08 18.08 0 0 1 -11 0v-.6392a5.5066 5.5066 0 0 1 5.5-5.5z"></path>
                  </svg>
                  <span>' . htmlspecialchars($_SESSION["username"]) . '</span>
                </button>

                <!-- Menù a tendina -->
                <div class="dropdown-content">
                  <!-- Pulsante “Modifica Profilo” -->
                  <a href="modifica_profilo.php" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
                      <path d="m17.074 30h-2.148c-1.038 0-1.914-.811-1.994-1.846l-.125-1.635c-.687-.208-1.351-.484-1.985-.824l-1.246 1.067c-.788.677-1.98.631-2.715-.104l-1.52-1.52c-.734-.734-.78-1.927-.104-2.715l1.067-1.246c-.34-.635-.616-1.299-.824-1.985l-1.634-.125c-1.035-.079-1.846-.955-1.846-1.993v-2.148c0-1.038.811-1.914 1.846-1.994l1.635-.125c.208-.687.484-1.351.824-1.985l-1.068-1.247c-.676-.788-.631-1.98.104-2.715l1.52-1.52c.734-.734 1.927-.779 2.715-.104l1.246 1.067c.635-.34 1.299-.616 1.985-.824l.125-1.634c.08-1.034.956-1.845 1.994-1.845h2.148c1.038 0 1.914.811 1.994 1.846l.125 1.635c.687.208 1.351.484 1.985.824l1.246-1.067c.787-.676 1.98-.631 2.715.104l1.52 1.52c.734.734.78 1.927.104 2.715l-1.067 1.246c.34.635.616 1.299.824 1.985l1.634.125c1.035.079 1.846.955 1.846 1.993v2.148c0 1.038-.811 1.914-1.846 1.994l-1.635.125c-.208.687-.484 1.351-.824 1.985l1.067 1.246c.677.788.631 1.98-.104 2.715l-1.52 1.52c-.734.734-1.928.78-2.715.104l-1.246-1.067c-.635.34-1.299.616-1.985.824l-.125 1.634c-.079 1.035-.955 1.846-1.993 1.846zm-5.835-6.373c.848.53 1.768.912 2.734 1.135.426.099.739.462.772.898l.18 2.341 2.149-.001.18-2.34c.033-.437.347-.8.772-.898.967-.223 1.887-.604 2.734-1.135.371-.232.849-.197 1.181.089l1.784 1.529 1.52-1.52-1.529-1.784c-.285-.332-.321-.811-.089-1.181.53-.848.912-1.768 1.135-2.734.099-.426.462-.739.898-.772l2.341-.18h-.001v-2.148l-2.34-.18c-.437-.033-.8-.347-.898-.772-.223-.967-.604-1.887-1.135-2.734-.232-.37-.196-.849.089-1.181l1.529-1.784-1.52-1.52-1.784 1.529c-.332.286-.81.321-1.181.089-.848-.53-1.768-.912-2.734-1.135-.426-.099-.739-.462-.772-.898l-.18-2.341-2.148.001-.18 2.34c-.033.437-.347.8-.772.898-.967.223-1.887.604-2.734 1.135-.37.232-.849.197-1.181-.089l-1.785-1.529-1.52 1.52 1.529 1.784c.285.332.321.811.089 1.181-.53.848-.912 1.768-1.135 2.734-.099.426-.462.739-.898.772l-2.341.18.002 2.148 2.34.18c.437.033.8.347.898.772.223.967.604 1.887 1.135 2.734.232.37.196.849-.089 1.181l-1.529 1.784 1.52 1.52 1.784-1.529c.332-.287.813-.32 1.18-.089z" fill="#ffff"></path>
                      <path d="m16 23c-3.859 0-7-3.141-7-7s3.141-7 7-7 7 3.141 7 7-3.141 7-7 7zm0-12c-2.757 0-5 2.243-5 5s2.243 5 5 5 5-2.243 5-5-2.243-5-5-5z" fill="#ffff"></path>
                    </svg>
                    Modifica Profilo
                  </a>

                  <!-- Pulsante “Aggiungi Amico” -->
                  <a href="aggiungi_amico.php" class="dropdown-item">
                    <svg
                      class="lucide lucide-user-round-plus"
                      stroke-linejoin="round"
                      stroke-linecap="round"
                      stroke-width="2"
                      stroke="#ffff"
                      fill="none"
                      viewBox="0 0 24 24"
                      height="24"
                      width="24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path d="M2 21a8 8 0 0 1 13.292-6"></path>
                      <circle r="5" cy="8" cx="10"></circle>
                      <path d="M19 16v6"></path>
                      <path d="M22 19h-6"></path>
                    </svg>
                    Aggiungi Amico
                  </a>

                  <!-- Pulsante “Richieste Ricevute” -->
                  <a href="richieste_ricevute.php" class="dropdown-item">
                    <svg
                      class="lucide lucide-envelope"
                      stroke-linejoin="round"
                      stroke-linecap="round"
                      stroke-width="2"
                      stroke="#ffff"
                      fill="none"
                      viewBox="0 0 25 25"
                      height="25"
                      width="25"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path d="M4 8l8-6 8 6v10H4V8z"/>
                      <path d="M4 8l8 6 8-6"/>
                    </svg>
                    Richieste Ricevute
                  </a>

                  <!-- Pulsante “Lista Amici” -->
                  <a href="lista_amici.php" class="dropdown-item">
                    <svg
                      class="lucide lucide-users-round"
                      stroke-linejoin="round"
                      stroke-linecap="round"
                      stroke-width="2"
                      stroke="#ffff"
                      fill="none"
                      viewBox="0 0 24 24"
                      height="24"
                      width="24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path d="M18 21a8 8 0 0 0-16 0"></path>
                      <circle r="5" cy="8" cx="10"></circle>
                      <path d="M22 20c0-3.37-2-6.5-4-8a5 5 0 0 0-.45-8.3"></path>
                    </svg>
                    Lista Amici
                  </a>

                  <!-- Pulsante “Logout” -->
                  <a href="logout.php" class="dropdown-item">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384.971 384.971">
                      <!-- Primo path -->
                      <path d="M180.455,360.91H24.061V24.061h156.394c6.641,0,12.03-5.39,12.03-12.03s-5.39-12.03-12.03-12.03H12.03
                              C5.39,0.001,0,5.39,0,12.031V372.94c0,6.641,5.39,12.03,12.03,12.03h168.424c6.641,0,12.03-5.39,12.03-12.03
                              C192.485,366.299,187.095,360.91,180.455,360.91z" fill="#ffff"/>
                      <!-- Secondo path -->
                      <path d="M381.481,184.088l-83.009-84.2c-4.704-4.752-12.319-4.74-17.011,0c-4.704,4.74-4.704,12.439,0,17.179l62.558,63.46H96.279
                              c-6.641,0-12.03,5.438-12.03,12.151c0,6.713,5.39,12.151,12.03,12.151h247.74l-62.558,63.46c-4.704,4.752-4.704,12.439,0,17.179
                              c4.704,4.752,12.319,4.752,17.011,0l82.997-84.2C386.113,196.588,386.161,188.756,381.481,184.088z" fill="#ffff"/>
                    </svg>
                    Logout
                  </a>
                </div>
              </div>';
          } else {
              echo '<a href="index.php">Home</a>';
              echo '<a href="games.php">Games</a>';
              echo '<a href="about.php">About</a>';
              echo '<a href="login.html">Login</a>';
          }
        ?>
    </div>
  </nav>
</header>
