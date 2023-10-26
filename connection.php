<?php

$db = new PDO('sqlite:todo_list.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);