<?php
if (isset($_POST['sign-out'])) {
    session_unset();
    session_destroy();

    header("Location: index.html");
} else {
    header("Location: todo_list.html");
}