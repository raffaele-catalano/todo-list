<?php

try {
    $db = new PDO('sqlite:todo_list.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("DROP TABLE IF EXISTS users");
    $db->exec("DROP TABLE IF EXISTS users_groups");
    $db->exec("DROP TABLE IF EXISTS groups");
    $db->exec("DROP TABLE IF EXISTS tasks");

    $db->exec("CREATE TABLE IF NOT EXISTS users(
        id INTEGER PRIMARY KEY UNIQUE,
        username VARCHAR NOT NULL,
        first_name VARCHAR NOT NULL,
        last_name VARCHAR NOT NULL,
        email VARCHAR NOT NULL,
        pwd VARCHAR NOT NULL
    )");

    $usersData = [
        ['raf_9', 'Raffaele', 'Catalano', 'rc@gmail.com', '12345678'],
        ['diegoDG', 'Diego', 'Di Guglielmo', 'ddg@gmail.com', '12345678'],
        ['daniela55', 'Daniela', 'Nardiello', 'dn@gmail.com', '12345678'],
        ['vo9', 'Victor', 'Osimhen', 'vo9@gmail.com', '12345678'],
    ];

    foreach ($usersData as $userData) {
        $username = $userData[0];
        $firstName = $userData[1];
        $lastName = $userData[2];
        $email = $userData[3];
        $password = $userData[4];

        $insertUser = $db->prepare("INSERT INTO users (username, first_name, last_name, email, pwd) VALUES (?, ?, ?, ?, ?)");
        $insertUser->execute([$username, $firstName, $lastName, $email, $password]);
    }

    $db->exec("CREATE TABLE IF NOT EXISTS users_groups(
        user_id INTEGER,
        group_id INTEGER,
        FOREIGN KEY (user_id) REFERENCES users (id),
        FOREIGN KEY (group_id) REFERENCES groups (id)
    )");

    $userGroupData = [
        [1, 1],
        [4, 1],
        [2, 2],
        [3, 2],
        [1, 3],
        [2, 3],
        [3, 3],
    ];

    foreach ($userGroupData as $relation) {
        $userId = $relation[0];
        $groupId = $relation[1];

        $insertRelation = $db->prepare("INSERT INTO users_groups (user_id, group_id) VALUES (?, ?)");
        $insertRelation->execute([$userId, $groupId]);
    }

    $db->exec("CREATE TABLE IF NOT EXISTS groups(
        id INTEGER PRIMARY KEY UNIQUE,
        group_name VARCHAR
    )");

    $groupData = [
        ['Forza Napoli'],
        ['Centralix Admin'],
        ['Centralix'],
    ];

    foreach ($groupData as $group) {
        $groupName = $group[0];

        $insertGroup = $db->prepare("INSERT INTO groups (group_name) VALUES (?)");
        $insertGroup->execute([$groupName]);
    }

    $db->exec("CREATE TABLE IF NOT EXISTS groups_tasks(
        group_id INTEGER,
        task_id INTEGER,
        FOREIGN KEY (group_id) REFERENCES groups (id),
        FOREIGN KEY (task_id) REFERENCES tasks (id)
    )");

    $groupTaskData = [
        [1, 1],
        [1, 2],
        [2, 3],
        [3, 1],
        [3, 4],
    ];

    foreach ($groupTaskData as $relation) {
        $groupId = $relation[0];
        $taskId = $relation[1];

        $insertGroupTask = $db->prepare("INSERT INTO groups_tasks (group_id, task_id) VALUES (?, ?)");
        $insertGroupTask->execute([$groupId, $taskId]);
    }

    $db->exec("CREATE TABLE IF NOT EXISTS tasks(
        id INTEGER PRIMARY KEY UNIQUE,
        title VARCHAR NOT NULL,
        due_date DATETIME,
        completed BOOLEAN NOT NULL,
        task_timestamp DATETIME,
        user_id INTEGER,
        group_id INTEGER,
        FOREIGN KEY (user_id) REFERENCES users (id),
        FOREIGN KEY (group_id) REFERENCES groups (id)
    )");
    

    $taskData = [
        ['Ordinare una pizza', '31-10-2023 20:00', 0, '2023-10-25 11:00:00', 1, null],
        ['Comprare un nuovo monitor', '27-10-2023 15:30', 0, '2023-10-26 10:00:00', 2, 2],
        ['Fissare una call con il candidato', '26-10-2023 12:30', 0, '2023-10-25 10:00:00', 3, 2],
        // altri tasks qui
    ];

    foreach ($taskData as $task) {
        $title = $task[0];
        $dueDate = $task[1];
        $completed = $task[2];
        $taskTimestamp = $task[3];
        $userId = $task[4];
        $groupId = $task[5];

        $insertTask = $db->prepare("INSERT INTO tasks (title, due_date, completed, task_timestamp, user_id, group_id) VALUES (?, ?, ?, ?, ?, ?)");
        $insertTask->execute([$title, $dueDate, $completed, $taskTimestamp, $userId, $groupId]);
    }
    
} catch (PDOException $error) {
    die('Errore nella connesione al DB:' . $error->getMessage());
}

?>