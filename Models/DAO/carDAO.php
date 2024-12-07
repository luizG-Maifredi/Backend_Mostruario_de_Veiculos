<?php
require_once ('/../config/database.php');
require_once ('../DTO/Car.php');


 class CarDAO{
    private $conn;

    public static function insertCar(Car $car){

        $this->conn = Database::getConnection();

        

        $this->conn = Database::closeConnection();
    }

    public static function updateCar(){

    }

    public static function deleteCar(){

    }
    

    public static function selectCar(){

    }

    public static function selectAllCars(){

    }
    
}

?>