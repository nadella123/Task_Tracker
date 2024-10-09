<?php
session_start();
require_once "db.php"; 

if (isset($_POST['task_id'])) {
    $taskId = $_POST['task_id'];
    $completed = isset($_POST['completed']) ? 1 : 0;

    $sql = "UPDATE tasks SET completed = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $completed, $taskId);
    mysqli_stmt_execute($stmt);
}

// Redirect back to index
header("Location: index.php");
exit;
