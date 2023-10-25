<?php
// Connessione al database
try {
    $db = new PDO('sqlite:todo_list.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Recupera l'ID del task e il nuovo stato dal corpo della richiesta
    $data = json_decode(file_get_contents("php://input"));

    if (!isset($data->id) || !isset($data->completed)) {
        echo json_encode(['error' => 'ID del task o stato mancante']);
        exit;
    }

    // Aggiorna lo stato del task nel database
    $taskId = $data->id;
    $completed = $data->completed;

    $updateTask = $db->prepare("UPDATE tasks SET completed = ? WHERE id = ?");
    $updateTask->execute([$completed, $taskId]);

    echo json_encode(['success' => true]);

} catch (PDOException $error) {
    echo json_encode(['error' => 'Errore nella connessione al database']);
}
?>
