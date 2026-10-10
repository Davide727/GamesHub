document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('aggiungiAmicoForm');
    const usernameInput = document.getElementById('username_destinatario');
    let datalist = document.getElementById('usernamesDatalist'); 

    if (!datalist) {
        datalist = document.createElement('datalist');
        datalist.id = 'usernamesDatalist';
        document.body.appendChild(datalist); 
    }
    usernameInput.setAttribute('list', 'usernamesDatalist'); 

    let debounceTimeout;

    usernameInput.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        const query = this.value.trim();

        if (query.length > 0) {
            debounceTimeout = setTimeout(() => {
                fetch(`cerca_username.php?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        datalist.innerHTML = '';
                        data.forEach(username => {
                            const option = document.createElement('option');
                            option.value = username;
                            datalist.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Errore durante la ricerca degli username:', error);
                    });
            }, 300); 
        } else {
            datalist.innerHTML = ''; 
        }
    });

    form.addEventListener('submit', function(event) {
        event.preventDefault(); 

        const formData = new FormData(form);
        formData.append('azione', 'aggiungi_amico');

        fetch('aggiungi_amico.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Successo!',
                    text: data.message,
                    timer: 1500,
                    showConfirmButton: false
                });
                form.reset(); 
                datalist.innerHTML = ''; 
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            console.error('Errore durante l\'invio della richiesta:', error);
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: 'Si è verificato un errore durante l\'invio della richiesta.',
            });
        });
    });
});