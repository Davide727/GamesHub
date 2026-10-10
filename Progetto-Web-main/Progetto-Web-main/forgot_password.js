/*
 - Gestisce la reimpostazione della password.
 - Utilizza la libreria SweetAlert per mostrare messaggi di errore e successo.
 - Implementa la logica asincrona per la verifica di username ed email.
 - Permette all'utente di inserire e validare una nuova password dopo la verifica.
 - Invia la nuova password al server per l'aggiornamento nel database.
*/
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('forgotPasswordForm');
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');
    let currentUsername = ''; // Variabile globale per memorizzare l'username

    if (error === 'notexists') {
        Swal.fire({
            title: 'Errore di recupero',
            text: 'L\'utente non esiste.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then(() => {
            window.history.replaceState({}, document.title, "forgot_password.html");
        });
    }

    form.addEventListener('submit', async function(event) {
        event.preventDefault(); 

        const username = document.getElementById('username').value;
        const email = document.getElementById('email').value;

        if (!username) {
            Swal.fire({
                title: 'Errore',
                text: 'Inserisci l\'username.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (!email) {
            Swal.fire({
                title: 'Errore',
                text: 'Inserisci l\'email.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            Swal.fire({
                title: 'Errore',
                text: 'Inserisci un\'email valida (es. nome@dominio.it).',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Invia username ed email per la verifica
        try {
            const response = await fetch('forgot_password.php', { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(username)}&email=${encodeURIComponent(email)}`
            });

            const data = await response.json();

            if (data.success) {
                currentUsername = username; // Memorizza l'username
                // Username ed email verificati, mostra i campi per la nuova password
                form.innerHTML = `
                    <div class="flex-column">
                        <label>Nuova Password</label>
                    </div>
                    <div class="inputForm">
                        <input type="password" name="new_password" placeholder="Nuova password" class="input" id="new_password"/>
                    </div>
                    <div class="flex-column">
                        <label>Conferma Nuova Password</label>
                    </div>
                    <div class="inputForm">
                        <input type="password" name="confirm_new_password" placeholder="Conferma nuova password" class="input" id="confirm_new_password"/>
                    </div>
                    <button class="button-submit" type="button" id="resetPasswordButton">Reimposta password</button>
                    <p class="p">Password ricordata? <a href="login.html" class="span">Accedi</a><br>
                    Torna alla home <a href="index.php" class="span">Home</a></p>
                `;

                // Aggiungi event listener al nuovo pulsante di reset password
                document.getElementById('resetPasswordButton').addEventListener('click', handleResetPassword);

            } else {
                Swal.fire({
                    title: 'Errore',
                    text: data.error || 'Username ed email non corrispondono.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }

        } catch (error) {
            console.error('Errore nella verifica:', error);
            Swal.fire({
                title: 'Errore di connessione',
                text: 'Si è verificato un errore durante la comunicazione con il server.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });

    async function handleResetPassword() {
        const newPassword = document.getElementById('new_password').value;
        const confirmNewPassword = document.getElementById('confirm_new_password').value;

        if (!newPassword) {
            Swal.fire({
                title: 'Errore',
                text: 'Inserisci la nuova password.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        const passwordPattern = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/;
        if (!passwordPattern.test(newPassword)) {
            Swal.fire({
                title: 'Errore',
                text: 'La password deve avere 8-16 caratteri, almeno 1 maiuscola e 1 numero.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (!confirmNewPassword) {
            Swal.fire({
                title: 'Errore',
                text: 'Conferma la nuova password.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (newPassword !== confirmNewPassword) {
            Swal.fire({
                title: 'Errore',
                text: 'Le password non corrispondono.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Invia la nuova password e l'username per l'aggiornamento
        try {
            const response = await fetch('forgot_password_reset.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `username=${encodeURIComponent(currentUsername)}&new_password=${encodeURIComponent(newPassword)}`
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    title: 'Password reimpostata!',
                    text: 'La tua password è stata aggiornata con successo. Ora puoi effettuare il login.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'login.html';
                });
            } else {
                Swal.fire({
                    title: 'Errore',
                    text: data.error || 'Impossibile reimpostare la password.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }

        } catch (error) {
            console.error('Errore nel reset password:', error);
            Swal.fire({
                title: 'Errore di connessione',
                text: 'Si è verificato un errore durante la comunicazione con il server.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    }
});