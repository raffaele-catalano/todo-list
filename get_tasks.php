<?php
session_start();
$userId = $_SESSION['user_id'];
try {
    include 'connection.php';

    // ! seleziona tutti i task creati dall'utente OPPURE seleziona i task che fanno parte di tutti i gruppi di cui l'utente fa parte ↓
    // ! è selezionato anche il nome utente preso dalla tabella users avente id uguale all'id dell'autore della task ↓
    // $query = $db->query('SELECT * FROM tasks WHERE user_id = ? OR group_id IN (SELECT group_id FROM users_groups WHERE user_id = ?)');
    $query = $db->query('SELECT *,(SELECT username FROM users WHERE users.id = tasks.user_id) AS username, (SELECT group_name FROM groups WHERE groups.id = tasks.group_id) AS group_name FROM tasks WHERE user_id = ? OR group_id IN (SELECT group_id FROM users_groups WHERE user_id = ?)');
    $query->execute([$userId, $userId]);
    $tasks = $query->fetchAll(PDO::FETCH_ASSOC);

    // i dati vengono restituiti come JSON
    echo json_encode($tasks);

} catch (PDOException $error) {
    echo json_encode(['error' => 'Errore nella connessione al database']);
}
?>
