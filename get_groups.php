<?php
session_start();
$userId = $_SESSION['user_id'];
try {
    include 'connection.php';

    // query SQL per ottenere i gruppi dal database
    // $getGroups = $db->query("SELECT id, group_name FROM groups");
    $getGroups = $db->query("SELECT id, group_name FROM groups WHERE groups.id IN (SELECT group_id FROM users_groups WHERE users_groups.user_id = ?)");

    $getGroups->execute([$userId]);
    $groups = $getGroups->fetchAll(PDO::FETCH_ASSOC);

    // invia i gruppi come risposta JSON
    header('Content-Type: application/json');
    echo json_encode($groups);

} catch (PDOException $error) {
    die('Errore nella connessione al DB: ' . $error->getMessage());
}
?>
