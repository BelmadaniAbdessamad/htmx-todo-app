<?php
require_once "./db.php";
class Todo
{

    public function __construct(private string $value, private bool $done = false)
    {
    }

    public static function render(string $value, bool $done, int $id): string
    {
        $done_class = $done ? "'done'" : " ";
        $actions = "";
        if (!$done) {
            $actions = "<button class='done-button'  hx-swap='outerHTML'  hx-target='#todo-" . $id . "' hx-put='api.php?action=update-todo&id=" . $id . "'>Done</button>";
        }
        $actions .= "<button class='delete-button'  hx-target='#todo-" . $id . "' hx-swap'outerHTML'  hx-delete='api.php?action=delete-todo&id=" . $id . "'>Delete</button>";


        return "<li id='todo-" . $id . "' class=" . $done_class . ">  <b>" . $value . "</b>  <span class='actions'>" . $actions . " </span></li>";
    }

    public function insertTodo()
    {
        try {
            $query = "INSERT INTO todos (value) VALUES (?)";
            $statement = Db::getInstance()->prepare($query);
            $statement->bind_param('s', $this->value);
            $statement->execute();
            $insertedId  = Db::getInstance()->insert_id;
            Db::getInstance()->close();
            if (isset($insertedId)) return Todo::render($this->value, false, $insertedId);
            //here i want to check if the isert is suceess and i wan to grab the id of the iserted todo
        } catch (Exception $e) {
        }
    }
    public static function setDone(int $id)
    {
        if (!isset($id)) return;

        try {

            $query = "UPDATE todos SET done=true WHERE id=?";
            $statement = Db::getInstance()->prepare($query);
            $statement->bind_param('i', $id);
            $success = $statement->execute();

            if ($success) {
                $todo = Todo::getTodo($id);
                if (!$todo) return;
                return Todo::render($todo['value'], $todo['done'] == null ? false : $todo['done'], $id);
            }
        } catch (Exception $e) {
        }
    }

    public static function getTodos()
    {
        $query = "SELECT * FROM todos";
        $result = Db::getInstance()->query($query);
        $todos = "";
        // Check if the query was successful
        if ($result) {
            
                // Fetch data from the result set
                while ($row = $result->fetch_assoc()) {
                    // Access individual columns in each row
                    $todos .= Todo::render($row['value'], $row['done'] == null ? false : $row['done'], $row['id']);
                }
                // Free the result set
                $result->free();
            
        } else {
            echo "Error: " . $query . "<br>" . Db::getInstance()->error;
        }

        // Close the connection
        Db::getInstance()->close();
        return $todos;
    }

    public static function getTodo(int $id)
    {
        if (!isset($id)) {
            return false;
        }

        try {
            $query = "SELECT * FROM todos WHERE id=?";
            $statement = Db::getInstance()->prepare($query);
            $statement->bind_param('i', $id);
            $success = $statement->execute();

            if ($success) {
                $result = $statement->get_result();
                $todo = $result->fetch_assoc();
                Db::getInstance()->close();
                return $todo;
            } else {
                Db::getInstance()->close();
                return false;
            }
        } catch (Exception $e) {
            // Handle the exception, e.g., log the error or display a message
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
