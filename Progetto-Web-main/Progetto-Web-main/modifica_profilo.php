<?php
/*
 Include la sessione per mantenere lo stato dell'utente e reindirizzarlo se non loggato
 La pagina permette all'utente di:
 - Aggiornare il proprio nome utente tramite un form.
 - Aggiornare la propria email tramite un form.
 - Aggiornare la propria password tramite un form.
 - Eliminare il proprio account, tramite un pulsante che attiva una funzione JavaScript.
 Le operazioni di aggiornamento e eliminazione sono gestite tramite chiamate AJAX
*/
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Modifica Profilo</title>
    
    <!-- Collega il nuovo file CSS -->
    <link rel="stylesheet" href="modifica_profilo.css">
    <link rel="icon" href="immagini/favicon.png" type="image/x-icon">
</head>
<body>
    <div class="profile-container">
        <h1>Modifica Profilo</h1>

        <div id="username_result"></div>
        <form id="username_form">
            <label for="username">Nome utente:</label>
            <input type="text" name="username" id="username">
            <button type="submit">Aggiorna Nome Utente</button>
        </form>

        <div id="email_result"></div>
        <form id="email_form">
            <label for="email">Email:</label>
            <input type="text" name="email" id="email">
            <button type="submit">Aggiorna Email</button>
        </form>

        <div id="password_result"></div>
        <form id="password_form">
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <button type="submit">Aggiorna Password</button>
        </form>

        <div class="login-link">
          Loggati con i nuovi dati: 
          <a href ="login.html">Clicca Qui</a>
        </div>

        <div class="centered-button">
            <button class="animated-button" onclick="window.location.href='index.php'">
                <svg viewBox="0 0 24 24" class="arr-2" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"/>
                </svg>
                <span class="text">Torna alla Home</span>
                <span class="circle"></span>
                <svg viewBox="0 0 24 24" class="arr-1" xmlns="http://www.w3.org/2000/svg">
                <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"/>
                </svg>
            </button>
        </div>
        <div class="delete-account-section">
            <p class="delete-warning">
                Per eliminare l'account, cliccare il pulsante a destra.<br>
                <strong>Attenzione:</strong> l'operazione non è reversibile.
            </p>
            <button class="delete-account-btn" onclick="eliminaAccount()">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 14" class="svgIcon trash-top">
                <g clip-path="url(#clip0_35_24)">
                    <path fill="white" d="M20.8232 2.62734L19.9948 4.21304C19.8224 4.54309 19.4808 4.75 19.1085 4.75H4.92857C2.20246 4.75 0 6.87266 0 9.5C0 12.1273 2.20246 14.25 4.92857 14.25H64.0714C66.7975 14.25 69 12.1273 69 9.5C69 6.87266 66.7975 4.75 64.0714 4.75H49.8915C49.5192 4.75 49.1776 4.54309 49.0052 4.21305L48.1768 2.62734C47.3451 1.00938 45.6355 0 43.7719 0H25.2281C23.3645 0 21.6549 1.00938 20.8232 2.62734Z"/>
                </g>
                <defs><clipPath id="clip0_35_24"><rect fill="white" height="14" width="69"/></clipPath></defs>
                </svg>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 57" class="svgIcon trash-bottom">
                <g clip-path="url(#clip0_35_22)">
                    <path fill="white" d="M64.0023 1.0648C64.0397 0.4882 63.5822 0 63.0044 0H5.99556C5.4178 0 4.96025 0.4882 4.99766 1.0648L8.19375 50.3203C8.44018 54.0758 11.6746 57 15.5712 57H53.4288C57.3254 57 60.5598 54.0758 60.8062 50.3203L64.0023 1.0648Z"/>
                </g>
                <defs><clipPath id="clip0_35_22"><rect fill="white" height="57" width="69"/></clipPath></defs>
                </svg>
            </button>
        </div>
    </div>
</body>
<script src="modifica_profilo.js"></script>
<script>
    function eliminaAccount() {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Non potrai recuperare il tuo account dopo l'eliminazione!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sì, elimina!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('elimina_account.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json' 
                },
                body: JSON.stringify({}) 
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Errore di rete o risposta non JSON dal server: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        'Eliminato!',
                        data.message,
                        'success'
                    ).then(() => {
                        window.location.href = 'index.php'; 
                    });
                } else {
                    Swal.fire(
                        'Errore!',
                        data.message,
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Errore durante la richiesta di eliminazione:', error);
                Swal.fire(
                    'Errore!',
                    'Si è verificato un problema durante l\'eliminazione dell\'account. Riprova più tardi.',
                    'error'
                );
            });
        }
    });
}
</script>
</html>
