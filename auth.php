<?php
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$password = $data['password'];

// if ($_SERVER["REQUEST_METHOD"] == "POST") {

//     $email = $_POST["email"];
//     $password = $_POST["password"];

// }else{
//     echo json_encode(['error' => 'Errore nella ricezione dei dati']);
//     die;
// }

try {
    include 'connection.php';

    // query per cercare l'utente con email e password corrispondenti
    $query = $db->prepare('SELECT * FROM users WHERE email = ? AND pwd = ?');
    $query->execute([$email, $password]);
    $user = $query->fetch();

    if ($user) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        // utente autenticato con successo
        echo json_encode(['authenticated' => true]);
    } else {
        // autenticazione fallita
        echo json_encode(['authenticated' => false]);
    }
} catch (PDOException $error) {
    // eventuali errori di connessione al database
    echo json_encode(['error' => 'Errore nella connessione al database']);
}
?>
