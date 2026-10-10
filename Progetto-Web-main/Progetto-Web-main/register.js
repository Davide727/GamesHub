document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    const registerButton = document.getElementById('registerButton');

    registerButton.addEventListener('click', async (e) => {
        e.preventDefault(); 

        const formData = new FormData(registerForm);

        try {
            const response = await fetch('register.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                Swal.fire({
                    title: 'Registrazione effettuata!',
                    text: 'Effettua il login.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'login.html';
                });
            } else {
                const errors = data.errors || {};
                let errorMessage = '';
                let errorTitle = 'Errore di registrazione';
                let errorIcon = 'error';
                const errorMessages = [];

                if (errors.username) {
                    errorMessages.push(`Username: ${errors.username}`);
                }
                if (errors.email) {
                    errorMessages.push(`Email: ${errors.email}`);
                }
                if (errors.password) {
                    errorMessages.push(`Password: ${errors.password}`);
                }
                if (errors.confirm_password) {
                    errorMessages.push(`Conferma Password: ${errors.confirm_password}`);
                }
                if (errors.terms) {
                    errorMessages.push(`Termini e Condizioni: ${errors.terms}`);
                }
                if (data.error === 'exists') {
                    errorMessages.push('Username o email già registrati.');
                } else if (data.error === 'registration_failed') {
                    errorMessages.push('Si è verificato un errore durante la registrazione.');
                } else if (data.error) {
                    errorMessages.push(`Errore: ${data.error}`);
                }

                if (errorMessages.length > 0) {
                    errorMessage = errorMessages.join('<br>'); 
                } else {
                    errorMessage = 'Si è verificato un errore durante la registrazione.'; 
                    errorIcon = 'warning';
                }

                Swal.fire({
                    title: errorTitle,
                    html: errorMessage, 
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