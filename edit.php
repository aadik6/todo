<?php
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $detail = $_POST['detail'];

    $sql = "UPDATE todolist SET todo_name='$name', todo_details='$detail' WHERE todo_id=$id";
    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }
    $conn->close();
}
?>
