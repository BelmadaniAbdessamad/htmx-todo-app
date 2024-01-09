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

        return "<li class=" . $done_class . "> " . $id . " - " . $value . " </li>";
    }

    public function insertTodo()
    {
        try {
            $query = "INSERT INTO todos (value) VALUES (:todoValue)";
            $statement = Db::getInstance()->prepare($query);
            $statement->bind_param(':todoValue', $this->value, PDO::PARAM_STR);
            $statement->execute();
            Db::getInstance()->close();
        } catch (PDOException $e) {
        }
    }

    public static function getTodos()
    {
        $query = "SELECT * FROM todos";
        $result = Db::getInstance()->query($query);

        // Check if the query was successful
        if ($result) {

            $todos = "";
            // Fetch data from the result set
            while ($row = $result->fetch_assoc()) {
                // Access individual columns in each row
                $todos += Todo::render($row['value'], $row['done'], $row['id']);
            }

            return $todos;

            // Free the result set
            $result->free();
        } else {
            echo "Error: " . $query . "<br>" . Db::getInstance()->error;
        }

        // Close the connection
        Db::getInstance()->close();
    }
}
