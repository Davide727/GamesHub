document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');

    loginButton.addEventListener('click', async (e) => {
        e.preventDefault(); 

        const formData = new FormData(loginForm);

        try {
            const response = await fetch('login.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                window.location.href = 'games.php'; 
            } else {
                let errorMessage = 'Errore durante il login.';
                let errorTitle = 'Errore di login';
                let errorIcon = 'error';

                switch (data.error) {
                    case 'empty_username':
                        errorMessage = 'Inserisci l\'username.';
                        break;
                    case 'empty_password':
                        errorMessage = 'Inserisci la password.';
                        break;
                    case 'invalid_password':
                        errorMessage = 'La password deve avere 8-16 caratteri, 1 maiuscola e 1 numero.';
                        break;
                    case 'notexists':
                        errorMessage = 'L\'utente non esiste.';
                        break;
                    case 'password':
                        errorMessage = 'Password errata.';
                        break;
                    default:
                        console.error('Errore non gestito dal server:', data.error);
                        errorMessage = 'Si è verificato un errore inaspettato.';
                        errorIcon = 'warning';
                        break;
                }

                Swal.fire({
                    title: errorTitle,
                    text: errorMessage,
                    icon: errorIcon,
                    confirmButtonText: 'OK'
                });
            }
        } catch (error) {
            console.error('Errore nella chiamata fetch:', error);
            Swal.fire({
                title: 'Errore di connessione',
                text: 'Si è verificato un errore durante la comunicazione con il server.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
});