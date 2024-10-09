<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $taskId = $_POST['task_id'];
    $userId = $_SESSION['user_id'];

    $sql = "DELETE FROM tasks WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $taskId, $userId);
    mysqli_stmt_execute($stmt);
    header("Location: index.php");
}
?>
