<?php
require_once "db.php";



if (isset($_GET['task_id'])) {
    $taskId = $_GET['task_id'];
    $sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY deadline ASC";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $task = mysqli_fetch_assoc($result);
}

if (isset($_POST['update'])) {
    $updatedTask = $_POST['task'];

    $sql = "UPDATE tasks SET task = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $updatedTask, $taskId);
    if (mysqli_stmt_execute($stmt)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error updating task.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Edit Task</title>
</head>

<body>
    <div class="container mt-4">
        <h3>Edit Task</h3>
        <form action="edit_task.php?task_id=<?php echo $taskId; ?>" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="task" value="<?php echo htmlspecialchars($task['task']); ?>" required>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-outline-primary" value="Update Task" name="update">
            </div>
        </form>
        <div>
            <a href="index.php" class="btn btn-outline-secondary mt-2">Cancel</a>
        </div>
    </div>
</body>

</html>