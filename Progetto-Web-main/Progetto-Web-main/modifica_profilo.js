/*
 Funzionalità principali:
 - Intercetta gli eventi di submit dei form per la modifica di username, email e password.
 - Impedisce l'invio tradizionale dei form, gestendo l'invio tramite richieste asincrone.
 - Comunica con i file PHP dedicati (`modifica_username.php`, `modifica_email.php`, `modifica_password.php`) per effettuare gli aggiornamenti nel database.
 - Utilizza la libreria SweetAlert (`Swal`) per fornire feedback visivo all'utente in caso di successo o errore.
 - In caso di successo, mostra un popup di conferma e svuota il campo di input corrispondente.
 - In caso di errore (validazione lato server fallita, problemi di database, ecc.), mostra un popup di errore con il messaggio fornito dal server.
 - Gestisce anche gli errori di connessione durante la comunicazione con il server, mostrando un apposito popup.
*/
document.addEventListener('DOMContentLoaded', function() {
    const usernameForm = document.getElementById('username_form');
    const emailForm = document.getElementById('email_form');
    const passwordForm = document.getElementById('password_form');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    usernameForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        const username = usernameInput.value;

        try {
            const response = await fetch('modifica_username.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'username=' + encodeURIComponent(username)
            });
            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    title: 'Successo!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    usernameInput.value = ''; 
                });
            } else {
                Swal.fire({
                    title: 'Errore',
                    text: data.error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        } catch (error) {
            console.error('Errore nella richiesta:', error);
            Swal.fire({
                title: 'Errore di connessione',
                text: 'Si è verificato un errore durante la comunicazione con il server.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });

    emailForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        const email = emailInput.value;

        try {
            const response = await fetch('modifica_email.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'email=' + encodeURIComponent(email)
            });
            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    title: 'Successo!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    emailInput.value = ''; 
                });
            } else {
                Swal.fire({
                    title: 'Errore',
                    text: data.error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        } catch (error) {
            console.error('Errore nella richiesta:', error);
            Swal.fire({
                title: 'Errore di connessione',
                text: 'Si è verificato un errore durante la comunicazione con il server.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });

    passwordForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        const password = passwordInput.value;

        try {
            const response = await fetch('modifica_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'password=' + encodeURIComponent(password)
            });
            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    title: 'Successo!',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    passwordInput.value = ''; 
                });
            } else {
                Swal.fire({
                    title: 'Errore',
                    text: data.error,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        } catch (error) {
            console.error('Errore nella richiesta:', error);
            Swal.fire({
                title: 'Errore di connessione',
                text: 'Si è verificato un errore durante la comunicazione con il server.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});