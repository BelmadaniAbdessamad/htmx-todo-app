<?php 
$db = null;
try {
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "todos";
    
    // Create a connection to the database
    $db = new mysqli($host, $username, $password, $database);
    
} catch (\Throwable $th) {
    //throw $th;
}
