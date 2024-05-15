<?php

$server = "localhost";
$username = "root";
$dpassword = "";
$db = "todo_project";

$conn = new mysqli($server, $username, $dpassword, $db);

if ($conn->connect_error) {
    die("connection failed :" . $conn->connect_error);
}

?>