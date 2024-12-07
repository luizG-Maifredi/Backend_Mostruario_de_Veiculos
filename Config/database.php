<?php
class Database {
    private static $host = 'localhost';
    private static $db_name = 'concessionaria';
    private static $username = 'root';
    private static $password = 'root';
    public static  $conn;


    public static function getConnection(){
        self::$conn = null;

        try {
            self::$conn = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$username, self::$password);
            self::$conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Erro na conexão: " . $exception->getMessage();
        }
        return self::$conn;
    }

    public static function closeConnection(){
        return self::$conn = null;
    }
}
?>