/*
 Variabili Globali:
 - `playerGrid`: griglia del giocatore.
 - `enemyGrid`: griglia del computer.
 - `rotateBtn`: ruotare le navi durante il piazzamento.
 - `statusDisplay`: mostrare messaggi di stato all'utente.
 - `newGameBtn`: iniziare una nuova partita.
 - `sunkMessage`: visualizzare i messaggi di affondamento delle navi.
 - `width`: Larghezza (e altezza) della griglia di gioco (10x10), definisce il numero di celle per lato.
 - `shipTypes`: Array di oggetti che definiscono i tipi di navi presenti nel gioco, specificando il loro nome e la loro lunghezza in celle.
 - `BASE_IMAGE`: celle della griglia, rappresentante lo stato di "acqua".
 - `MISS_IMAGE`: attacco del giocatore o del computer manca una nave.
 - `HIT_IMAGE`: attacco del giocatore o del computer colpisce una parte di una nave.
 - `playerSquares`: Array che conterrà i div con classe 'square-inner' rappresentanti le celle della griglia del giocatore.
 - `enemySquares`: Array simile a `playerSquares`, ma per la griglia del computer.
 - `playerShips`: Array che conterrà oggetti rappresentanti le navi del giocatore, memorizzando informazioni come posizione, colpi subiti e orientamento.
 - `enemyShips`: Array simile a `playerShips`, ma per le navi del computer.
 - `placedShips`: Numero intero che tiene traccia di quante navi il giocatore ha posizionato con successo sulla propria griglia.
 - `lastHit`: Variabile per memorizzare l'indice dell'ultima cella colpita sulla griglia del giocatore, utile per strategie di attacco successive.
 - `currentDirection`: Variabile per memorizzare la direzione (orizzontale o verticale) in cui sta cercando di colpire dopo un colpo riuscito.
 - `potentialTargets`: Array utilizzato per memorizzare gli indici delle celle adiacenti all'ultimo colpo, considerate potenziali parti della stessa nave.
 - `huntingMode`: Variabile booleana per indicare se sta ancora cercando casualmente le navi (`true`) o se è in modalità di "targeting" dopo un colpo (`false`).
 - `placementOrientation`: Variabile booleana che indica l'orientamento corrente con cui il giocatore sta posizionando le navi

 Funzioni Principali:
 - `createBoard(grid)`: Crea dinamicamente la griglia di gioco (sia per il giocatore che per il computer).
    Svuota l'HTML esistente della griglia, aggiunge la classe 'board', crea un numero di celle pari a `width * width`,
    imposta ID e classi CSS per ogni cella (esterna ed interna), imposta l'immagine di sfondo predefinita (acqua) per le celle interne,
    aggiunge le celle alla griglia e memorizza gli elementi interni in un array che viene restituito.
 - `checkForHit(squareInner, squares, ships, isPlayer)`: Verifica se un attacco (click su una cella) ha colpito una nave.
    Riceve l'elemento cliccato, l'array delle celle della griglia attaccata, l'array delle navi dell'avversario e un booleano che indica chi sta attaccando.
    Determina l'ID della cella colpita, cerca se una nave occupa quella posizione, aggiorna l'array degli 'hits' della nave se colpita,
    cambia l'immagine di sfondo della cella in 'colpito' o 'acqua', aggiorna il messaggio di stato e verifica se la nave è affondata.
    Restituisce `true` se c'è stata una hit, `false` altrimenti.
 - `updateSunkShip(ship, squares)`: Aggiorna graficamente le celle di una nave affondata, mostrando l'immagine appropriata per ogni parte.
    Riceve l'oggetto nave affondata e l'array delle celle della griglia a cui appartiene.
    Ordina le posizioni della nave, determina l'orientamento, itera su ogni posizione per ottenere l'immagine corretta tramite `getSunkImage`,
    imposta l'immagine di sfondo della cella corrispondente e mostra un messaggio di affondamento temporaneo.
 - `getSunkImage(index, size, orientation)`: Restituisce il percorso dell'immagine GIF corretta per una parte specifica di una nave affondata (testa, corpo, coda) 
    in base all'indice, alla dimensione totale e all'orientamento della nave.
 - `checkGameOver(isPlayer)`: Controlla se tutte le navi di un giocatore sono state affondate, determinando la fine del gioco.
    Riceve un booleano che indica se stiamo controllando la fine del gioco dal punto di vista del giocatore.
    Utilizza il metodo `every()` sugli array `playerShips` e `enemyShips` per verificare se tutte le navi hanno subito un numero di colpi pari alla loro dimensione.
    Se il gioco è finito, rimuove gli event listener per impedire ulteriori azioni e chiama `showGameOver()` con il messaggio appropriato.
 - `showGameOver(msg)`: Mostra la schermata di fine gioco, visualizzando un messaggio di vittoria o sconfitta e rendendo visibile il bottone per una nuova partita.
 - `handleRotation()`: Cambia l'orientamento delle navi del giocatore tra orizzontale e verticale e aggiorna il testo del bottone di rotazione.
 - `calculateShipPositions(startIndex, size, orientation)`: Calcola gli indici delle celle occupate da una nave in base alla cella di partenza, alla dimensione 
    e all'orientamento. Esegue controlli per assicurarsi che la nave non sborderi dalla griglia (orizzontalmente o verticalmente).
    Restituisce un array di indici o `null` se la posizione non è valida.
 - `placePlayerShip(e)`: Gestisce l'evento di click sulla griglia del giocatore per piazzare una nave.
    Ottiene l'indice della cella cliccata, verifica se tutte le navi sono già state piazzate, calcola le posizioni della nave corrente,
    verifica eventuali collisioni con navi già piazzate, aggiorna lo sfondo delle celle con l'immagine della nave,
    aggiunge la classe 'ship' alle celle occupate, salva le informazioni della nave nell'array `playerShips` e passa alla nave successiva o avvia il gioco.
 - `getShipImage(index, size, orientation)`: Restituisce il nome del file GIF corretto per una porzione di nave del giocatore durante il piazzamento, 
    in base all'indice, alla dimensione e all'orientamento.
 - `startGame()`: Avvia il gioco dopo che il giocatore ha piazzato tutte le sue navi.
    Rimuove l'event listener per il piazzamento, nasconde il bottone di rotazione, aggiorna il messaggio di stato,
    chiama `placeEnemyShips()` per posizionare le navi del computer e aggiunge l'event listener per gli attacchi del giocatore sulla griglia nemica.
 - `placeEnemyShips()`: Posiziona casualmente le navi del computer sulla sua griglia.
    Itera sui tipi di navi, genera casualmente un orientamento e una posizione di partenza, calcola le posizioni,
    verifica che non ci siano sovrapposizioni con altre navi, aggiorna lo sfondo delle celle (senza mostrarlo al giocatore) e salva le informazioni della nave 
    nell'array `enemyShips`.
 - `playerTurn(e)`: Gestisce il turno del giocatore quando clicca su una cella della griglia nemica per attaccare.
    Ottiene la cella cliccata, verifica che non sia già stata colpita, rimuove temporaneamente l'event listener,
    chiama `checkForHit()` per verificare il colpo, controlla se il computer ha perso e, se non c'è stata una hit, passa il turno al computer dopo un breve ritardo.
 - `computerTurn()`: Gestisce il turno del computer, scegliendo una cella da attaccare sulla griglia del giocatore.
    Implementa una logica di attacco che include una fase di ricerca casuale (`huntingMode`) e una fase di targeting più mirata dopo aver colpito una nave.
    Chiama `checkForHit()` per l'attacco, e se non c'è stata una hit, riabilita il turno del giocatore.
 - `updateStats(vittoria)`: Invia una richiesta POST a un file PHP (`battaglianavale_aggiornastatistiche.php`) per aggiornare le statistiche di gioco 
    (vittorie/sconfitte).
 - `getRandomValidTarget()`: Restituisce un indice casuale di una cella sulla griglia del giocatore che non è ancora stata colpita (né acqua né colpita).
 - `isValidTarget(index)`: Verifica se una cella specificata sulla griglia del giocatore è un bersaglio valido (non ancora colpita).
 - `initGame()`: Funzione di inizializzazione che resetta lo stato del gioco, crea le griglie, resetta gli array delle navi,
    imposta lo stato iniziale dell'interfaccia (visibilità dei bottoni, messaggi di stato) e aggiunge gli event listener necessari per iniziare 
    il piazzamento delle navi del giocatore.

 Gestione Eventi:
 - L'intero script è avvolto in un listener per l'evento `DOMContentLoaded` per assicurarsi che il codice venga eseguito solo dopo che l'HTML è completamente 
   caricato.
 - `playerGrid.addEventListener("click", placePlayerShip)`: Gestisce i click sulla griglia del giocatore durante la fase di piazzamento delle navi.
 - `rotateBtn.addEventListener("click", handleRotation)`: Gestisce i click sul bottone per ruotare le navi.
 - `enemyGrid.addEventListener("click", playerTurn)`: Gestisce i click sulla griglia del computer durante la fase di attacco del giocatore.
 - `newGameBtn.addEventListener("click", ...)`: Gestisce il click sul bottone "Nuova Partita" ricaricando la pagina.

 Avvio del Gioco:
 - La funzione `initGame()` viene chiamata alla fine dello script per inizializzare e avviare il gioco.
*/

document.addEventListener("DOMContentLoaded", () => {

    // ====================== VARIABILI GLOBALI ======================
    const playerGrid = document.getElementById("player-grid");
    const enemyGrid = document.getElementById("computer-grid");
    const rotateBtn = document.getElementById("rotate");
    const statusDisplay = document.getElementById("message");
    const newGameBtn = document.getElementById("new-game");
    const sunkMessage = document.getElementById("sunk-message"); // Messaggi di navi affondate

    const width = 10;
    const shipTypes = [
      { name: "portaerei", size: 5 },
      { name: "corazzata", size: 4 },
      { name: "incrociatore", size: 3 },
      { name: "sottomarino", size: 3 },
      { name: "cacciatorpediniere", size: 2 },
    ];

    const BASE_IMAGE = "immagini/battaglia/batt100.gif";
    const MISS_IMAGE = "immagini/battaglia/batt102.gif";
    const HIT_IMAGE = "immagini/battaglia/batt103.gif";

    let playerSquares = [];
    let enemySquares = [];
    let playerShips = [];
    let enemyShips = [];
    let placedShips = 0;

    // Variabili per l'"intelligenza" del computer
    let lastHit = null;
    let currentDirection = null;
    let potentialTargets = [];
    let huntingMode = true;

    // Orientamento navi giocatore (true = orizzontale, false = verticale)
    let placementOrientation = true; 

    // ==================== FUNZIONI PRINCIPALI ======================

    function createBoard(grid) {
      // Svuota e ricrea la griglia
      grid.innerHTML = "";
      grid.classList.add("board");
      const squares = [];
      for (let i = 0; i < width * width; i++) {
        const square = document.createElement("div");
        square.dataset.id = i;
        square.classList.add("square");
        const inner = document.createElement("div");
        inner.className = "square-inner";
        // Di base, tutte le celle hanno l’immagine di “mare”
        inner.style.backgroundImage = `url('${BASE_IMAGE}')`;
        square.appendChild(inner);
        grid.appendChild(square);
        squares.push(inner); // Salviamo l'elemento "inner" nel nostro array
      }
      return squares;
    }

    /**
     * @param {HTMLElement} squareInner -> la .square-inner cliccata
     * @param {Array} squares -> array contenente i .square-inner
     * @param {Array} ships -> lista navi (positions, hits, ecc.)
     * @param {Boolean} isPlayer -> true se stiamo colpendo il giocatore, false se stiamo colpendo il computer
     * @returns {Boolean} => true se colpito, false se acqua
     */
    function checkForHit(squareInner, squares, ships, isPlayer) {
      // Prima di tutto, ricaviamo l'id intero della cella
      const parentSquare = squareInner.parentElement; 
      const id = parseInt(parentSquare.dataset.id);

      // Cerchiamo la nave colpita (se esiste)
      let hitShip = null;
      for (const ship of ships) {
        if (ship.positions.includes(id)) {
          // Abbiamo colpito questa nave
          ship.hits.push(id);
          hitShip = ship;
          break;
        }
      }

      // Se hitShip è rimasto null, significa che NON l'abbiamo colpita
      if (!hitShip) {
        squareInner.style.backgroundImage = `url('${MISS_IMAGE}')`; 
        statusDisplay.textContent = "Acqua!";
        return false;
      }

      // Altrimenti è un colpo andato a segno
      squareInner.style.backgroundImage = `url('${HIT_IMAGE}')`;
      statusDisplay.textContent = "Colpito!";

      // Se tutte le celle di quella nave sono colpite, allora è affondata
      if (hitShip.hits.length === hitShip.positions.length) {
        updateSunkShip(hitShip, squares);
        checkGameOver(isPlayer);
      }

      return true;
    }

    // Segna graficamente una nave come affondata
    function updateSunkShip(ship, squares) {
      const sorted = [...ship.positions].sort((a, b) => a - b);
      const orientation = sorted[1] - sorted[0] === 1 ? "horizontal" : "vertical";

      sorted.forEach((pos, index) => {
        // Calcola immagine corretta: coda, corpo, punta...
        const img = getSunkImage(index, sorted.length, orientation);
        squares[pos].style.backgroundImage = `url('${img}')`;
      });

      // Mostra messaggio di affondamento in alto
      sunkMessage.textContent = `${ship.name.toUpperCase()} AFFONDATA!`;
      // Dopo qualche secondo puliamo il messaggio
      setTimeout(() => {
        if (sunkMessage.textContent.includes("AFFONDATA")) {
          sunkMessage.textContent = "";
        }
      }, 3000);
    }

    // Restituisce la gif giusta per la parte di nave affondata
    function getSunkImage(index, size, orientation) {
      // Immagini per la nave affondata
      const parts = {
        horizontal: {
          first: "immagini/battaglia/batt201.gif",
          middle: "immagini/battaglia/batt202.gif",
          last: "immagini/battaglia/batt203.gif",
        },
        vertical: {
          first: "immagini/battaglia/batt204.gif",
          middle: "immagini/battaglia/batt205.gif",
          last: "immagini/battaglia/batt206.gif",
        },
      };

      if (index === 0) return parts[orientation].first;
      if (index === size - 1) return parts[orientation].last;
      return parts[orientation].middle;
    }

    // Controlla se il gioco è finito
    function checkGameOver(isPlayer) {
      const playerFleetDestroyed = playerShips.every(
        (ship) => ship.hits.length === ship.positions.length
      );
      const enemyFleetDestroyed = enemyShips.every(
        (ship) => ship.hits.length === ship.positions.length
      );

      if (playerFleetDestroyed || enemyFleetDestroyed) {
        // Disabilitiamo i click
        enemyGrid.removeEventListener("click", playerTurn);
        playerGrid.removeEventListener("click", placePlayerShip);

        // Messaggio finale
        const message = enemyFleetDestroyed
          ? "HAI VINTO! Tutte le navi nemiche affondate!"
          : "HAI PERSO! Tutte le tue navi sono affondate!";
        showGameOver(message);
      }
    }

    // Mostra schermata di fine gioco
    function showGameOver(msg) {
      statusDisplay.textContent = msg;
      newGameBtn.classList.remove("hidden");
      // Rimuoviamo listener per evitare ulteriori azioni
      enemyGrid.removeEventListener("click", playerTurn);
    }

    // ==================== GESTIONE ORIENTAMENTO ====================

    // Cambia da orizzontale a verticale e viceversa
    function handleRotation() {
      placementOrientation = !placementOrientation;
      rotateBtn.textContent = `Orientamento: ${
        placementOrientation ? "Orizzontale" : "Verticale"
      }`;
    }

    // ================== POSIZIONAMENTO DELLE NAVI ==================

    /**
     * Calcola le posizioni di una nave data la cella di partenza e l’orientamento
     */
    function calculateShipPositions(startIndex, size, orientation) {
      const positions = [];
      for (let i = 0; i < size; i++) {
        let index;
        if (orientation === "horizontal") {
          index = startIndex + i;
          // Controllo se sforiamo la riga
          if (
            Math.floor(index / width) !== Math.floor(startIndex / width)
          ) {
            return null; // Sfora di riga
          }
        } else {
          index = startIndex + width * i;
          // Controllo se sforiamo la griglia in verticale
          if (index >= width * width) {
            return null;
          }
        }
        positions.push(index);
      }
      return positions;
    }

    /**
     * Funzione che gestisce il click per piazzare una nave del giocatore
     */
    function placePlayerShip(e) {
      const square = e.target.closest(".square");
      if (!square) return;
      const index = parseInt(square.dataset.id);

      if (placedShips >= shipTypes.length) {
        return; // Già piazzate tutte
      }

      const ship = shipTypes[placedShips];
      const orientation = placementOrientation ? "horizontal" : "vertical";
      const shipCells = calculateShipPositions(index, ship.size, orientation);
      if (!shipCells) {
        statusDisplay.textContent = "Posizione non valida!";
        return;
      }

      // Verifichiamo eventuali collisioni con navi già piazzate
      for (let pos of shipCells) {
        if (
          playerSquares[pos].parentElement.classList.contains("ship")
        ) {
          statusDisplay.textContent = "Collisione rilevata!";
          return;
        }
      }

      // Se tutto ok, piazziamo la nave
      shipCells.forEach((pos, idx) => {
        // Cambiamo background
        const imageName = getShipImage(idx, ship.size, orientation);
        playerSquares[pos].style.backgroundImage = `url('immagini/battaglia/${imageName}')`;
        // Per evitare di confonderci, aggiungiamo .ship al DIV padre
        playerSquares[pos].parentElement.classList.add("ship");
      });

      // Salviamo la nave
      playerShips.push({
        name: ship.name,
        positions: shipCells,
        orientation,
        hits: [],
      });

      placedShips++;
      if (placedShips < shipTypes.length) {
        // Deve piazzare la prossima nave
        statusDisplay.textContent = `Posiziona: ${shipTypes[placedShips].name}`;
      } else {
        // Ha piazzato tutte, avviamo il gioco
        startGame();
      }
    }

    // Restituisce il nome della gif corretta per la porzione di nave (orizzontale o verticale)
    function getShipImage(index, size, orientation) {
      // per orientamento orizzontale
      const parts = {
        horizontal: {
          0: "batt1.gif", // coda
          [size - 1]: "batt5.gif", // punta
          default: "batt2.gif", // corpo
        },
        vertical: {
          0: "batt6.gif",  // coda
          [size - 1]: "batt10.gif", // punta
          default: "batt7.gif", // corpo
        },
      };
      return parts[orientation][index] || parts[orientation].default;
    }

    // Avvia il gioco dopo che il giocatore ha piazzato tutte le navi
    function startGame() {
      // Rimuoviamo l'event listener di piazzamento
      playerGrid.removeEventListener("click", placePlayerShip);
      rotateBtn.classList.add("hidden");

      statusDisplay.textContent = "GAME STARTED! Affonda le navi nemiche!";
      // Piazziamo navi nemiche
      placeEnemyShips();
      // Attacchi del giocatore
      enemyGrid.addEventListener("click", playerTurn);
    }

    // ===================== NAVI DEL COMPUTER =======================

    function placeEnemyShips() {
      enemyShips = []; // reset eventuale
      shipTypes.forEach((ship) => {
        let placed = false;
        while (!placed) {
          const orientation = Math.random() < 0.5 ? "horizontal" : "vertical";
          const start = Math.floor(Math.random() * width * width);
          const shipSquares = calculateShipPositions(start, ship.size, orientation);
          if (
            shipSquares &&
            !shipSquares.some((pos) =>
              enemySquares[pos].parentElement.classList.contains("ship")
            )
          ) {
            // Piazza
            shipSquares.forEach((pos) => {
              enemySquares[pos].parentElement.classList.add("ship");
              // Non mostriamo la nave (resta l’immagine di base) per evitare di farla vedere al player
              enemySquares[pos].style.backgroundImage = `url('${BASE_IMAGE}')`;
            });
            enemyShips.push({
              name: ship.name,
              positions: shipSquares,
              hits: [],
              orientation,
            });
            placed = true;
          }
        }
      });
    }

    // ================== GESTIONE TURNI & ATTACCHI ==================

    function playerTurn(e) {
      const square = e.target.closest(".square");
      if (!square) return;

      // Evitiamo doppi click sulla stessa cella
      if (
        square.querySelector(".square-inner").style.backgroundImage.includes("batt102.gif") ||
        square.querySelector(".square-inner").style.backgroundImage.includes("batt103.gif")
      ) {
        return;
      }

      // Togliamo temporaneamente il listener, in caso di passaggio al computer
      enemyGrid.removeEventListener("click", playerTurn);

      const wasHit = checkForHit(
        square.querySelector(".square-inner"),
        enemySquares,
        enemyShips,
        false // stiamo colpendo il computer
      );

      // Controllo se il computer ha perso
      if (enemyShips.every((s) => s.hits.length === s.positions.length)) {
        showGameOver("HAI VINTO! Tutte le navi nemiche affondate!");
        updateStats(true)
        return;
      }

      // Se NON abbiamo colpito, passa il turno al computer
      if (!wasHit) {
        setTimeout(() => {
          if (!playerShips.every((s) => s.hits.length === s.positions.length)) {
            computerTurn();
          }
        }, 500);
      } else {
        // Se colpito, il giocatore continua
        enemyGrid.addEventListener("click", playerTurn);
      }
    }

    // Turno del computer
    function computerTurn() {
      // Se il computer ha già affondato tutto...
      if (playerShips.every((s) => s.hits.length === s.positions.length)) {
        showGameOver("HAI PERSO! Tutte le tue navi sono affondate!");
        updateStats(false);
        return;
      }

      // Se non c'è una cella "strategica", sceglie a caso
      let targetIndex = getRandomValidTarget();
      if (targetIndex == null) {
        // Se per qualche motivo non trova nulla, termina
        huntingMode = true;
        lastHit = null;
        enemyGrid.addEventListener("click", playerTurn);
        return;
      }

      const square = playerSquares[targetIndex];
      const wasHit = checkForHit(square, playerSquares, playerShips, true);
      // Se colpisce, prova a sparare di nuovo
      if (wasHit) {
        lastHit = targetIndex;
        setTimeout(computerTurn, 500);
      } else {
        huntingMode = true;
        enemyGrid.addEventListener("click", playerTurn);
      }
    }

    // =================== GESTIONE STATISTICHE =====================
    function updateStats(vittoria) {
      fetch('battaglianavale_aggiornastatistiche.php', { // Invio una richiesta POST al file PHP per aggiornare le statistiche.
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'vittoria=' + vittoria // Invio il risultato della partita
      });
    }

    // Restituisce un indice casuale non ancora colpito
    function getRandomValidTarget() {
      let index;
      let attempts = 0;
      do {
        index = Math.floor(Math.random() * width * width);
        attempts++;
      } while (!isValidTarget(index) && attempts < 1000);

      return isValidTarget(index) ? index : null;
    }

    // Verifica se una certa cella non è già "hit" o "miss"
    function isValidTarget(index) {
      const bg = playerSquares[index].style.backgroundImage;
      if (bg.includes("batt102.gif") || bg.includes("batt103.gif")) {
        return false;
      }
      return true;
    }

    // ===================== FUNZIONE DI INIZIALIZZAZIONE =============

    function initGame() {
      // Reset e creazione board
      playerSquares = createBoard(playerGrid);
      enemySquares = createBoard(enemyGrid);

      playerShips = [];
      enemyShips = [];
      placedShips = 0;
      lastHit = null;
      currentDirection = null;
      potentialTargets = [];
      huntingMode = true;

      // Interfaccia
      newGameBtn.classList.add("hidden");
      rotateBtn.classList.remove("hidden");
      rotateBtn.textContent = "Orientamento: Orizzontale";
      statusDisplay.textContent = "Posiziona le tue navi";
      sunkMessage.textContent = "";

      // Rimuoviamo eventuali listener vecchi
      enemyGrid.removeEventListener("click", playerTurn);
      playerGrid.removeEventListener("click", placePlayerShip);

      // Aggiungiamo event listeners
      playerGrid.addEventListener("click", placePlayerShip);
      rotateBtn.addEventListener("click", handleRotation);
    }

    // ====================== GESTIONE BOTTONI ========================

    // Bottone NUOVA PARTITA
    newGameBtn.addEventListener("click", () => {
      location.reload(); 
    });

    // Avvio
    initGame();
});
