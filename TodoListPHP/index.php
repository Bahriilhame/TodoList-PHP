<?php

$mysqli = new mysqli('localhost', 'root', 'root', 'todolist');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "SELECT * FROM todo ORDER BY created_at DESC";
$result = $mysqli->query($sql);

$taches = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $taches[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $taskId = $_POST['id'];

        if ($action === 'new') {
            $newTask = $_POST['title'];
            $sql = "INSERT INTO todo (title) VALUES ('$newTask')";
            $mysqli->query($sql);
        } elseif ($action === 'delete') {
            $sql = "DELETE FROM todo WHERE id=$taskId";
            $mysqli->query($sql);
        } elseif ($action === 'toggle') {
            $sql = "UPDATE todo SET done = 1 - done WHERE id=$taskId";
            $mysqli->query($sql);
        }
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>To-Do List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">To-Do List</a>
    </nav>

    <div class="container mt-4">
        <form method="POST">
            <div class="form-group">
                <label for="title">New Task:</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <button type="submit" class="btn btn-primary" name="action" value="new">Add Task</button>
        </form>

        <ul class="list-group mt-4">
            <?php foreach ($taches as $tache) : ?>
                <li class="list-group-item <?php echo ($tache['done'] == 1) ? 'list-group-item-success' : 'list-group-item-warning'; ?>">
                    <?php echo $tache['title']; ?>
                    <form method="POST" class="float-right">
                        <input type="hidden" name="id" value="<?php echo $tache['id']; ?>">
                        <button type="submit" class="btn btn-success btn-sm" name="action" value="toggle">Toggle</button>
                        <button type="submit" class="btn btn-danger btn-sm" name="action" value="delete">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>