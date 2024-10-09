<?php
session_start();
require_once "db.php"; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = $_POST['task'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];
    $user_id = $_SESSION['user_id'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO tasks (task, priority, deadline, user_id) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Error preparing the statement: " . $conn->error); // Handle errors
    }

    // Bind parameters and execute
    $stmt->bind_param("sssi", $task, $priority, $deadline, $user_id);
    
    if ($stmt->execute()) {
        // Redirect to the main page to avoid form resubmission
        header("Location: index.php");
        exit;
    } else {
        echo "Error executing query: " . $stmt->error; // Display error if insertion fails
    }

    $stmt->close();
}
?>
