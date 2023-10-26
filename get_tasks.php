<?php
// connessione al database
try {
    include 'connection.php';

    // ottenere tutte le attività dal database
    $query = $db->query('SELECT * FROM tasks');
    $tasks = $query->fetchAll(PDO::FETCH_ASSOC);

    // i dati vengono restituiti come JSON
    echo json_encode($tasks);

} catch (PDOException $error) {
    echo json_encode(['error' => 'Errore nella connessione al database']);
}
?>
