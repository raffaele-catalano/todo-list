<?php
try {
    include 'connection.php';

    // recupera l'ID del task e il nuovo stato dal corpo della richiesta
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->id) || !isset($data->completed)) {
        echo json_encode(['error' => 'ID del task o stato mancante']);
        exit;
    }

    // aggiorna lo stato del task nel database
    $taskId = $data->id;
    $completed = $data->completed;

    $updateTask = $db->prepare("UPDATE tasks SET completed = ? WHERE id = ?");
    $updateTask->execute([$completed, $taskId]);

    echo json_encode(['success' => true]);

} catch (PDOException $error) {
    echo json_encode(['error' => 'Errore nella connessione al database']);
}
?>
