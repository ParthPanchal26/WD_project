<?php
session_start();

if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task']) && trim($_POST['task']) !== '') {
        $task = trim($_POST['task']);
        if (isset($_POST['task_index'])) {
            $index = (int)$_POST['task_index'];
            $_SESSION['tasks'][$index] = $task;
        } else {
            $_SESSION['tasks'][] = $task;
        }
    }

    if (isset($_POST['delete_task']) && isset($_POST['task_index'])) {
        $removeIndex = (int)$_POST['task_index'];
        if (isset($_SESSION['tasks'][$removeIndex])) {
            unset($_SESSION['tasks'][$removeIndex]);
        }
        $_SESSION['tasks'] = array_values($_SESSION['tasks']);
    }
}

$tasks = $_SESSION['tasks'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Task Manager App</title>
    <script>
        function storeTasksInLocalStorage(tasks) {
            localStorage.setItem('tasks', JSON.stringify(tasks));
        }

        window.onload = function() {
            const tasksFromPHP = <?php echo json_encode($tasks); ?>;
            storeTasksInLocalStorage(tasksFromPHP);
        }

        function editTask(index, task) {
            const input = document.getElementById('task-input');
            input.value = task;
            const form = document.getElementById('task-form');
            const taskIndexInput = document.createElement('input');
            taskIndexInput.type = 'hidden';
            taskIndexInput.name = 'task_index';
            taskIndexInput.value = index;
            form.appendChild(taskIndexInput);
        }
    </script>
</head>

<body>
    <div class="container">
        <h1 class="heading">Task Manager App</h1>
        <form class="form" method="POST" id="task-form">
            <div class="actions">
                <input class="inputBox" type="text" name="task" id="task-input" placeholder="Add a new task..." required />
                <button class="addBtn" type="submit">Add Task</button>
            </div>
        </form>
        <ul class="listBox" id="task-list">
            <?php foreach ($tasks as $index => $task): ?>
                <li class="lists">
                    <textarea cols=20 rows=3 name="" id="">

                        <?php echo htmlspecialchars($task); ?>
                    </textarea>
                    <button onclick="editTask(<?php echo $index; ?>, '<?php echo htmlspecialchars($task); ?>')" class="edit_btn">Edit</button>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="task_index" value="<?php echo $index; ?>">
                        <button class="delete_btn" type="submit" name="delete_task">Delete Task</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>