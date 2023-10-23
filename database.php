<?php

try {
    $db = new PDO('sqlite:todo_list.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->exec("CREATE TABLE IF NOT EXISTS users(
        id INTEGER PRIMARY KEY UNIQUE,
        username VARCHAR NOT NULL,
        first_name VARCHAR,
        last_name VARCHAR,
        email VARCHAR
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS users_groups(
        user_id INTEGER,
        group_id INTEGER,
        FOREIGN KEY (user_id) REFERENCES users (id),
        FOREIGN KEY (group_id) REFERENCES groups (id)
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS groups(
        id INTEGER PRIMARY KEY UNIQUE,
        group_name VARCHAR
    )");

    $db->exec("CREATE TABLE IF NOT EXISTS tasks(
        id INTEGER PRIMARY KEY UNIQUE,
        title VARCHAR NOT NULL,
        descr TEXT,
        due_date DATETIME,
        completed BOOLEAN NOT NULL,
        task_timestamp DATETIME,
        user_id INTEGER,
        FOREIGN KEY (user_id) REFERENCES users (id)
    )");
} catch (PDOException $error) {
    die('Errore nella connesione al DB:' . $error->getMessage());
}

?>