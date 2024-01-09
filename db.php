<?php 


class Db {
    private static mysqli $connection;
    private static $host = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $database = "todos";

    private function __construct()
    { 
    }

    public static function getInstance():mysqli
    {
        if(Db::$connection==null){
            try {
                Db::$connection =new mysqli(Db::$host, Db::$username,Db::$password, Db::$database);
            } catch (\Throwable $th) {
                //throw $th;
            }
           
        }
        return Db::$connection;
    }
}
