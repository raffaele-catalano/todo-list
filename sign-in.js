document.addEventListener('DOMContentLoaded', function() {
    const signInForm = document.querySelector('form');

    signInForm.addEventListener('submit', function(event) {
      event.preventDefault(); // Evita l'invio del modulo predefinito

      // Recupera i dati dell'utente (email e password)
      const email = signInForm.querySelector('input[type="email"]').value;
      const password = signInForm.querySelector('input[type="password"]').value;

      // Implementa la logica di autenticazione qui
      // Puoi utilizzare fetch() per inviare una richiesta al tuo server PHP per la verifica dell'autenticazione.
      // Esempio:
      fetch('auth.php', {
        method: 'POST',
        body: JSON.stringify({ email, password }),
        headers: {
          'Content-Type': 'application/json'
        }
      })
        .then(response => response.json())
        .then(data => {
          // Verifica la risposta dal server e reindirizza l'utente se l'autenticazione è riuscita
          if (data.authenticated) {
            window.location.href = 'todolist.html';
          } else {
            // Mostra un messaggio di errore se l'autenticazione non è riuscita
            alert('Autenticazione non riuscita. Riprova.');
          }
        })
        .catch(error => {
          console.error('Errore durante l\'autenticazione:', error);
        });
    });
  });
