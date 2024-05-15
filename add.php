<?php

session_start(); 
if(isset($_SESSION['user_id'])) { // Added if condition
  $userId = $_SESSION['user_id'];
}

include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $detail = $_POST['detail'];
    
    // Added $userId variable in the SQL query
    $sql = "INSERT INTO todolist (todo_name, todo_details, createdBy) VALUES ('$name', '$detail', '$userId')";
    if ($conn->query($sql) === TRUE) {
        $id = $conn->insert_id;
        $response = array(
            "id" => $id,
            "name" => $name,
            "detail" => $detail
        );
        echo json_encode($response);
    } else {
        echo json_encode(array("error" => $conn->error));
    }
    $conn->close();
}
?>
