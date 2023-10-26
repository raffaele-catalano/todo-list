document.addEventListener('DOMContentLoaded', function() {
    const signInForm = document.querySelector('form');

    signInForm.addEventListener('submit', function(event) {
      event.preventDefault(); // Evita l'invio del modulo predefinito

      // recupera i dati dell'utente (email e password)
    const email = signInForm.querySelector('input[type="email"]').value;
    const password = signInForm.querySelector('input[type="password"]').value;

      // Esegui una richiesta per l'autenticazione
        fetch('auth.php', {
            method: 'POST',
            body: JSON.stringify({ email, password }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
          // verifica la risposta dal server e reindirizza l'utente se l'autenticazione è riuscita
            if (data.authenticated) {
              window.location.href = 'todo_list.html'; // Aggiunto reindirizzamento
            } else {
              // mostra un messaggio di errore se l'autenticazione non è riuscita
                alert('Autenticazione non riuscita. Riprova.');
            }
        })
        .catch(error => {
            console.error('Errore durante l\'autenticazione:', error);
        });
    });
});
