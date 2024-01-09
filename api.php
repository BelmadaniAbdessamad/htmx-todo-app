

<?php
require_once "todo.php";
switch ($_GET['action']) {
    case 'post-todo':
        if (isset($_POST['todo']) &&  $_POST['todo'] != "") {
            $todo = new Todo($_POST['todo'], false);
            echo $todo->insertTodo();
        } else {
        }

        break;
    case 'todos-list':
        echo Todo::getTodos();
        break;

    default:
        # code...
        break;
}
