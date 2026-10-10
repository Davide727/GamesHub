/*
 Funzionalità principali:
 - Caricamento asincrono delle domande da un file JSON ('domande.json').
 - Selezione casuale di un set di 10 domande per ogni partita, con una distribuzione specifica per livello di difficoltà (2 facili, 4 medie, 4 difficili).
 - Visualizzazione dinamica della domanda corrente e delle relative risposte.
 - Implementazione di un timer per ogni domanda.
 - Gestione della selezione della risposta da parte del giocatore.
 - Verifica della correttezza della risposta selezionata.
 - Aggiornamento del guadagno del giocatore in base al livello raggiunto.
 - Evidenziazione visiva del livello corrente sulla scaletta dei premi.
 - Logica per la fine del gioco in caso di risposta errata o completamento di tutte le domande.
 - Calcolo del guadagno finale in caso di sconfitta.
 - Disabilitazione delle interazioni con le risposte al termine di una domanda.
 - Funzionalità per iniziare una nuova partita.
 - Adattamento dinamico del layout delle risposte in base alla larghezza dello schermo.
 - Tentativo di invio del guadagno finale al server tramite una chiamata POST AJAX.

 Elementi HTML utilizzati:
 - 'domanda': Div per visualizzare la domanda corrente.
 - 'risposte': Div contenitore per le opzioni di risposta.
 - 'guadagno': Div per visualizzare il guadagno corrente del giocatore.
 - 'nuovaPartita': Link per avviare una nuova partita.
 - '#scaletta ul': Lista non ordinata che rappresenta la scaletta dei premi.

 Variabili globali:
 - domandaCorrente: Indice della domanda attualmente visualizzata.
 - guadagno: Punteggio/guadagno corrente del giocatore.
 - giocoInCorso: Booleano che indica se una partita è attualmente in corso.
 - timer: Variabile per memorizzare l'intervallo del timer.
 - tempoRimasto: Numero di secondi rimanenti per rispondere alla domanda corrente.
 - guadagni: Array contenente i valori dei guadagni per ogni livello della scaletta.
 - domande: Array contenente tutte le domande caricate dal file JSON.
 - domandePartita: Array contenente le 10 domande selezionate per la partita corrente.
 
 Flusso di esecuzione principale:
 - Al caricamento della pagina, viene effettuata una chiamata fetch per caricare le domande dal file 'domande.json'.
 - Quando l'utente clicca sul link "Nuova Partita", viene chiamata la funzione 'iniziaGioco()'.
 - 'iniziaGioco()' seleziona casualmente 10 domande con la funzione 'selezionaDomande()' e avvia la visualizzazione della prima domanda con 'mostraDomanda()'.
 - 'mostraDomanda()' popola l'HTML con la domanda e le risposte, avvia il timer e evidenzia il livello corrente sulla scaletta.
 - Quando il giocatore clicca su una risposta, viene chiamata 'controllaRisposta()'.
 - 'controllaRisposta()' verifica se la risposta è corretta, aggiorna il guadagno, passa alla domanda successiva o termina il gioco con 'fineGioco()'.
 - Il timer viene aggiornato ogni secondo dalla funzione 'aggiornaTimer()', e se scade, il gioco termina.
 - 'fineGioco()' visualizza il risultato della partita e tenta di inviare il guadagno al server.
 - La funzione 'calcolaGuadagno()' determina il guadagno finale in caso di sconfitta.
 - Il layout delle risposte si adatta dinamicamente alla larghezza dello schermo grazie alla funzione 'adattaDimensioni()'.
*/
const domandaDiv = document.getElementById('domanda');
const risposteDiv = document.getElementById('risposte');
const guadagnoDiv = document.getElementById('guadagno');
const nuovaPartitaLink = document.getElementById('nuovaPartita');
const scaletta = document.getElementById('scaletta').querySelector('ul');

// Inizializza le variabili di gioco
let domandaCorrente = 0;
let guadagno = 0;
let giocoInCorso = false;
let timer;
let tempoRimasto = 10; 

// Array per i guadagni dalla scaletta
const guadagni = Array.from(scaletta.querySelectorAll('li')).map(li => parseInt(li.dataset.guadagno));

// Array per le domande caricate dal file JSON
let domande = [];
// Array per le domande selezionate per la partita corrente
let domandePartita = [];

// Carica le domande dal file JSON
fetch('domande.json')
    .then(response => response.json())
    .then(data => {
        domande = data;
    });

// Funzione per selezionare le domande per la partita corrente
function selezionaDomande() {
    domandePartita = [];

    // Filtra le domande per difficoltà
    const domandeFacili = domande.filter(d => d.difficolta === 1);
    const domandeMedie = domande.filter(d => d.difficolta === 2);
    const domandeDifficili = domande.filter(d => d.difficolta === 3);

    // Seleziona 2 domande facili casuali
    for (let i = 0; i < 2; i++) {
        const indiceRandom = Math.floor(Math.random() * domandeFacili.length);
        domandePartita.push(domandeFacili.splice(indiceRandom, 1)[0]);
    }

    // Seleziona 4 domande medie casuali
    for (let i = 0; i < 4; i++) {
        const indiceRandom = Math.floor(Math.random() * domandeMedie.length);
        domandePartita.push(domandeMedie.splice(indiceRandom, 1)[0]);
    }

    // Seleziona 4 domande difficili casuali
    for (let i = 0; i < 4; i++) {
        const indiceRandom = Math.floor(Math.random() * domandeDifficili.length);
        domandePartita.push(domandeDifficili.splice(indiceRandom, 1)[0]);
    }

    // Mescola le domande selezionate
    domandePartita.sort(() => 0.5 - Math.random());
}

// Funzione per mostrare la domanda corrente
function mostraDomanda() {
    domandaDiv.textContent = domandePartita[domandaCorrente].domanda;
    tempoRimasto = 10; 
    risposteDiv.innerHTML = '';
    domandePartita[domandaCorrente].risposte.forEach((risposta, index) => {
        const rispostaDiv = document.createElement('div');
        rispostaDiv.textContent = risposta;
        rispostaDiv.classList.add('risposta');
        rispostaDiv.addEventListener('click', () => controllaRisposta(index));
        risposteDiv.appendChild(rispostaDiv);
    });

    function adattaDimensioni() {
        if (window.innerWidth <= 768) {
            document.querySelector('.container').style.gridTemplateColumns = '1fr';
        } else {
            document.querySelector('.container').style.gridTemplateColumns = '1fr 1fr 1fr';
        }
    }
    window.addEventListener('resize', adattaDimensioni);
    window.addEventListener('load', adattaDimensioni);

    // Aggiungi le lettere alle risposte
    const risposte = document.querySelectorAll('.risposta');
    risposte.forEach((risposta, index) => {
        risposta.textContent = `${String.fromCharCode(65 + index)}. ${risposta.textContent}`;
    });

    // Aggiorna il guadagno visualizzato
    guadagnoDiv.textContent = `${guadagno}€`;

    // Aggiorna il timer e avvia il conto alla rovescia
    aggiornaTimer();
    timer = setInterval(aggiornaTimer, 1000);

    // Evidenzia il livello corrente sulla scaletta
    evidenziaLivello();
}

// Funzione per evidenziare il livello corrente sulla scaletta
function evidenziaLivello() {
    const livelli = scaletta.querySelectorAll('li');
    livelli.forEach((livello, index) => {
        livello.classList.remove('current-level');
        if (index === domandaCorrente) {
            livello.classList.add('current-level');
        }
    });
}

// Funzione per aggiornare il timer
function aggiornaTimer() {
    tempoRimasto--;
    timerDiv.textContent = `${tempoRimasto}s`;
    if (tempoRimasto === 0) {
        clearInterval(timer);
        fineGioco(false);
    }
}

// Funzione per controllare la risposta del giocatore
function controllaRisposta(index) {
    clearInterval(timer);

    // Evidenzia la risposta corretta e quella selezionata dal giocatore
    const risposte = document.querySelectorAll('.risposta');
    risposte.forEach((risposta, i) => {
        if (i === domandePartita[domandaCorrente].rispostaCorretta) {
            risposta.style.border = '2px solid green';
        } else if (i === index) {
            risposta.style.border = '2px solid red';
        }
    });

    setTimeout(() => {
        if (index === domandePartita[domandaCorrente].rispostaCorretta) {
            guadagno = guadagni[domandaCorrente];
            domandaCorrente++;
            if (domandaCorrente < domandePartita.length) {
                mostraDomanda();
            } else {
                fineGioco(true);
            }
        } else {
            fineGioco(false);
        }
    }, 1000);
}

// Funzione per terminare il gioco
function fineGioco(vittoria) {
    giocoInCorso = false;
    if (vittoria) {
        guadagnoDiv.textContent = `Hai vinto! Guadagno: ${guadagno}€!`;
    } else {
        // Calcola il guadagno in base al livello raggiunto
        guadagno = calcolaGuadagno(domandaCorrente);
        guadagnoDiv.textContent = `Hai perso! Guadagno: ${guadagno}€`;
    }

    // Disabilita i clic sulle risposte
    const risposte = document.querySelectorAll('.risposta');
    risposte.forEach(risposta => {
        risposta.style.pointerEvents = 'none';
    });

    // Invia il guadagno al server (da implementare)
    fetch('aggiornastatistiche_milionario.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `guadagno=${guadagno}`,
    });
}

// Funzione per calcolare il guadagno in base al livello raggiunto
function calcolaGuadagno(livello) {
    return livello > 0 ? guadagni[livello - 1] : 0;
}


const timerDiv = document.createElement('div');
timerDiv.classList.add('timer');

const guadagnoPosizione = document.getElementById('guadagno');
guadagnoPosizione.insertAdjacentElement('afterend', timerDiv);

timerDiv.textContent = `Tempo: 0 s`;

// Funzione per iniziare una nuova partita
function iniziaGioco() {
    selezionaDomande();
    domandaCorrente = 0;
    guadagno = 0;
    mostraDomanda();
    giocoInCorso = true;
}

// Aggiunge un listener per il click sul link "Nuova Partita"
nuovaPartitaLink.addEventListener('click', (e) => {
    e.preventDefault();
    if (giocoInCorso) {
        clearInterval(timer);
    }
    iniziaGioco();
});