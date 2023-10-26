<?php
session_start();
$userId = $_SESSION['user_id'];
try {
    include 'connection.php';

    // dati inviati dal client
    $data = json_decode(file_get_contents('php://input'));

    $taskTitle = $data->title;
    $dueDate = $data->dueDate;
    $groupId = $data->groupId;

    // query SQL per inserire un nuovo task nella tabella "tasks"
    $insertTask = $db->prepare("INSERT INTO tasks (title, due_date, completed, task_timestamp, group_id, user_id) VALUES (?, ?, 0, DATETIME('now'), ?, ?)");
    $insertTask->execute([$taskTitle, $dueDate, $groupId, $userId]);

    // verifica se l'inserimento è riuscito
    $response = array();
    if ($insertTask) {
        $response['success'] = true;
        $response['message'] = "Task aggiunto con successo!";
    } else {
        $response['success'] = false;
        $response['message'] = "Errore durante l'aggiunta del task.";
    }

    // invia la risposta al client come JSON
    header('Content-Type: application/json');
    echo json_encode($response);

} catch (PDOException $error) {
    die('Errore nella connessione al DB: ' . $error->getMessage());
}
?>
