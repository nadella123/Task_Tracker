<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <title>To-Do List</title>

</head>

<body>
    <div class="container-fluid d-flex justify-content-between align-items-center bg-dark p-3 mb-2">
        <h2 style="color: white;"><?php echo "Welcome, " . $_SESSION['fullname'] . "!"; ?></h2>
        <div>
            <a href="logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
    <div class="container mt-4">

        <?php
        $userId = $_SESSION['user_id'];
        $sql = "SELECT * FROM tasks WHERE user_id = ? ORDER BY deadline ASC";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);


        // Count completed tasks
        $completedCount = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['completed'] == 1) {
                $completedCount++;
            }
        }

        echo "<h4>Completed Tasks: $completedCount</h4>";
        mysqli_data_seek($result, 0);

        ?>

        <form action="todo.php" method="post" class="form-inline">
            <input type="text" name="task" class="form-control mr-2" placeholder="New Task" required>

            <select name="priority" class="form-control mr-2">
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>

            <input type="date" name="deadline" class="form-control mr-2">

            <button type="submit" class="btn btn-outline-dark">Add Task</button>
        </form>


        <table class="table table-striped mt-5">
            <thead>
                <tr>
                    <th>Sno</th>
                    <th>Task</th>
                    <th>Priority</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th class="d-flex justify-content-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    $color = getPriorityColor($row['priority']);
                    echo '<tr style="background-color: ' . $color . '">';

                    echo '<td>' . $counter++ . '</td>';
                    echo '<td>' . htmlspecialchars($row['task']) . '</td>';

                    // Display Priority
                    echo '<td>' . htmlspecialchars($row['priority']) . '</td>';


                    echo '<td>' . htmlspecialchars($row['deadline']) . '</td>';


                    echo '<td>';
                    echo '<form action="complete_task.php" method="post" class="form-inline">';
                    echo '<input type="hidden" name="task_id" value="' . $row['id'] . '">';
                    echo '<input type="checkbox" name="completed" value="1" ' . ($row['completed'] ? 'checked' : '') . ' onchange="this.form.submit()">';
                    echo '</form>';
                    echo '</td>';


                    echo '<td>';
                    echo '<div class="d-flex justify-content-around">';
                    echo '<form action="delete_task.php" method="post" class="form-inline me-2">';
                    echo '<input type="hidden" name="task_id" value="' . $row['id'] . '">';
                    echo '<button type="submit" class="btn btn-outline-danger">Delete</button>';
                    echo '</form>';

                    echo '<form action="edit_task.php" method="get" class="form-inline">';
                    echo '<input type="hidden" name="task_id" value="' . $row['id'] . '">';
                    echo '<button type="submit" class="btn btn-outline-warning">Edit</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
        <?php
        function getPriorityColor($priority)
        {
            switch ($priority) {
                case 'High':
                    return '#ffcccc';
                case 'Medium':
                    return '#ffffcc';
                case 'Low':
                    return '#ccffcc';
                default:
                    return '#ffffff';
            }
        }

        ?>

    </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>