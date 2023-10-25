<?php
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$password = $data['password'];

try {
    $db = new PDO('sqlite:todo_list.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Esegui una query per cercare l'utente con email e password corrispondenti
    $query = $db->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
    $query->execute([$email, $password]);
    $user = $query->fetch();

    if ($user) {
        // L'utente è autenticato con successo
        echo json_encode(['authenticated' => true]);
    } else {
        // Autenticazione fallita
        echo json_encode(['authenticated' => false]);
    }
} catch (PDOException $error) {
    // Gestisci eventuali errori di connessione al database
    echo json_encode(['error' => 'Errore nella connessione al database']);
}

?>
