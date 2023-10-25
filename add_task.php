<?php
try {
    $db = new PDO('sqlite:todo_list.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ricevi i dati inviati dal client
    $data = json_decode(file_get_contents('php://input'));

    $taskTitle = $data->title;
    $dueDate = $data->dueDate;
    $groupId = $data->groupId;

    // Esegui una query SQL per inserire un nuovo task nella tabella "tasks"
    $insertTask = $db->prepare("INSERT INTO tasks (title, due_date, completed, task_timestamp, user_id, group_id) VALUES (?, ?, 0, DATETIME('now'), ?, ?)");
    $insertTask->execute([$taskTitle, $dueDate, $userId, $groupId]);

    // Verifica se l'inserimento è riuscito
    $response = array();
    if ($insertTask) {
        $response['success'] = true;
        $response['message'] = "Task aggiunto con successo!";
    } else {
        $response['success'] = false;
        $response['message'] = "Errore durante l'aggiunta del task.";
    }

    // Invia la risposta al client come JSON
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (PDOException $error) {
    die('Errore nella connessione al DB: ' . $error->getMessage());
}
?>
