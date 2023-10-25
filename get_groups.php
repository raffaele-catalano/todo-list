<?php
try {
    $db = new PDO('sqlite:todo_list.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // query SQL per ottenere i gruppi dal database
    $getGroups = $db->query("SELECT id, group_name FROM groups");
    $groups = $getGroups->fetchAll(PDO::FETCH_ASSOC);

    // invia i gruppi come risposta JSON
    header('Content-Type: application/json');
    echo json_encode($groups);

} catch (PDOException $error) {
    die('Errore nella connessione al DB: ' . $error->getMessage());
}
?>
