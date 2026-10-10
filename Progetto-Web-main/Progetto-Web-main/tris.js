document.addEventListener('DOMContentLoaded', () => {
    const cells = document.querySelectorAll('.cell');
    const messageDiv = document.getElementById('message');
    const nuovaPartitaButton = document.getElementById('nuovaPartitaButton');

    function aggiornaVisualizzazione() {
        cells.forEach((cell, index) => {
            cell.textContent = tabella[index] === '-' ? '' : tabella[index];
        });
    }

    function checkVincitoreLocale() {
        const lines = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8],
            [0, 3, 6], [1, 4, 7], [2, 5, 8],
            [0, 4, 8], [2, 4, 6]
        ];
        for (let i = 0; i < lines.length; i++) {
            const [a, b, c] = lines[i];
            if (tabella[a] && tabella[a] === tabella[b] && tabella[a] === tabella[c]) {
                return tabella[a];
            }
        }
        if (!tabella.includes('-')) {
            return 'pareggio';
        }
        return null;
    }

    function gestisciMossa(index) {
        const giocatoreCorrente = (parseInt(turno) === 1 && parseInt(userId) === parseInt(giocatore1Id)) || 
                                  (parseInt(turno) === 2 && parseInt(userId) === parseInt(giocatore2Id));
        const turnoCorretto = (parseInt(turno) === 1 && parseInt(userId) === parseInt(giocatore1Id)) || 
                              (parseInt(turno) === 2 && parseInt(userId) === parseInt(giocatore2Id));
        const partitaIniziata = giocatore1Id && giocatore2Id;
        const partitaNonFinita = parseInt(vincitorePartita) === 0;
        if (tabella[index] === '-'  && partitaIniziata && turnoCorretto && partitaNonFinita) {
            const giocatoreCorrente = (parseInt(turno) === 1 && parseInt(userId) === parseInt(giocatore1Id)) || 
                                      (parseInt(turno) === 2 && parseInt(userId) === parseInt(giocatore2Id));
            const mioSimbolo = parseInt(userId) === parseInt(giocatore1Id) ? 'X' : 'O';
            const turnoCorretto = (parseInt(turno) === 1 && parseInt(userId) === parseInt(giocatore1Id)) || 
                                  (parseInt(turno) === 2 && parseInt(userId) === parseInt(giocatore2Id));
            const partitaIniziata = giocatore1Id && giocatore2Id;
            const partitaNonFinita = parseInt(vincitorePartita) === 0;

            if (partitaIniziata && turnoCorretto && partitaNonFinita) {
                tabella = tabella.substring(0, index) + mioSimbolo + tabella.substring(index + 1);
                aggiornaVisualizzazione();
                const vincitoreLocale = checkVincitoreLocale();

                let vincitoreServer = 0;
                if (vincitoreLocale === 'X') vincitoreServer = 1;
                else if (vincitoreLocale === 'O') vincitoreServer = 2;
                else if (vincitoreLocale === 'pareggio') vincitoreServer = 3;

                fetch('tris_azione_semplice.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `partita_id=${partitaId}&tabella=${tabella}&turno=${parseInt(turno) === 1 ? 2 : 1}&vincitore=${vincitoreServer}`
                })
                .then(response => response.text())
                .then(data => {
                    if (vincitoreServer) {
                        vincitorePartita = vincitoreServer;
                        mostraRisultatoPartita();
                    } else {
                        turno = parseInt(turno) === 1 ? 2 : 1;
                        mostraTurno();
                    }
                    aggiornaVisualizzazione();
                });
            } else if (!partitaIniziata) {
                messageDiv.textContent = 'Acquisizione ID giocatore...';
            } else if (!turnoCorretto && partitaNonFinita) {
                messageDiv.textContent = 'Non è il tuo turno.';
            } else if (parseInt(vincitorePartita) !== 0) {
                mostraRisultatoPartita();
            }
        } else {
            console.log('Mossa non valida o non è il turno.');
        }
    }

    function mostraTurno() {
        if (parseInt(vincitorePartita) === 0) {
            if (!giocatore1Id) {
                messageDiv.textContent = 'Acquisizione ID giocatore.';
            } else if (!giocatore2Id) {
                messageDiv.textContent = 'In attesa dell\'altro giocatore...';
            } else if (parseInt(turno) === 1 && parseInt(userId) === parseInt(giocatore1Id)) {
                messageDiv.textContent = 'È il tuo turno!';
            } else if (parseInt(turno) === 2 && parseInt(userId) === parseInt(giocatore2Id)) {
                messageDiv.textContent = 'È il tuo turno!';
            } else {
                messageDiv.textContent = 'Turno dell\'avversario';
            }
        } else {
            mostraRisultatoPartita();
        }
    }

    function mostraRisultatoPartita() {
        let messaggio = '';
        if (parseInt(vincitorePartita) === 3) {
            messaggio = 'Pareggio!';
        } else if ((parseInt(vincitorePartita) === 1 && parseInt(userId) === parseInt(giocatore1Id)) || 
                   (parseInt(vincitorePartita) === 2 && parseInt(userId) === parseInt(giocatore2Id))) {
            messaggio = 'Hai vinto!';
        } else if (parseInt(vincitorePartita) !== 0) {
            messaggio = 'Hai perso!';
        }
        messageDiv.textContent = messaggio;
    }

    nuovaPartitaButton.addEventListener('click', () => {
        window.location.href = 'tris.php';
    });

    cells.forEach((cell, index) => {
        cell.addEventListener('click', () => {
            gestisciMossa(index);
        });
    });

    if (parseInt(vincitorePartita) !== 0) {
        mostraRisultatoPartita();
    }

    aggiornaVisualizzazione();
    mostraTurno();
    setInterval(() => {
        fetch(`stato_partita_api.php?partita_id=${partitaId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    tabella = data.tabella;
                    turno = data.turno;
                    vincitorePartita = data.vincitore;
                    giocatore1Id = data.giocatore1_id;
                    giocatore2Id = data.giocatore2_id;
                    aggiornaVisualizzazione();
                    mostraTurno();
                }
            });
    }, 1000); 

});