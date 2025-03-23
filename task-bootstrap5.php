<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List with Bootstrap 5</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="text-center">To-Do List</h3>
            </div>
            <div class="card-body">
                <form method="POST" class="mb-3 d-flex">
                    <input type="text" name="task" class="form-control me-2" placeholder="Enter a new task" required>
                    <button type="submit" class="btn btn-success">Add Task</button>
                </form>
                
                <ul class="list-group">

                    <?php 

                        define("TASKS_FILE", "tasks.json");

                        function saveTasks($tasks) {
                            file_put_contents(TASKS_FILE, json_encode($tasks, JSON_PRETTY_PRINT));
                        }
                        
                        function loadTasks() {
                            return file_exists(TASKS_FILE) ? json_decode(file_get_contents(TASKS_FILE), true) ?? [] : [];
                        }
                        
                        $tasks = loadTasks();
                        
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (!empty(trim($_POST['task'] ?? ''))) {
                                $tasks[] = ["task" => htmlspecialchars(trim($_POST['task'])), "done" => false];
                                saveTasks($tasks);
                                header("Location: " . $_SERVER['PHP_SELF']);
                                exit;
                            } elseif (isset($_POST['delete'])) {
                                unset($tasks[$_POST['delete']]);
                                saveTasks(array_values($tasks));
                                header("Location: " . $_SERVER['PHP_SELF']);
                                exit;
                            } elseif (isset($_POST['toggle'])) {
                                $tasks[$_POST['toggle']]['done'] = !$tasks[$_POST['toggle']]['done'];
                                saveTasks($tasks);
                                header("Location: " . $_SERVER['PHP_SELF']);
                                exit;
                            }
                        }
                        
                        if (empty($tasks)) {
                            echo '<li class="list-group-item text-center">No tasks yet. Add one above!</li>';
                        } else {
                            foreach ($tasks as $index => $task) {
                                echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
                                echo '<form method="POST" class="d-inline-block">
                                        <input type="hidden" name="toggle" value="' . $index . '">
                                        <button type="submit" class="btn btn-link text-decoration-none ' . ($task['done'] ? 'text-muted text-decoration-line-through' : 'text-dark') . '">
                                            ' . $task['task'] . '
                                        </button>
                                    </form>';
                                echo '<form method="POST" class="d-inline-block">
                                        <input type="hidden" name="delete" value="' . $index . '">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>';
                                echo '</li>';
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>