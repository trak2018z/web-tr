<?php
require_once __DIR__.'/../base.php';
session_start();

if(!empty(trim($_POST["input-temp"]))){
    $temperature = trim($_POST["input-temp"]);
    if($temperature == "on")
    {
        $temperature = 1;
    }
    else
    {
        $temperature = 0;
    }
}
else
{
    $temperature = 0;
}
if(!empty(trim($_POST["input-humid"]))){
    $humidity = trim($_POST["input-humid"]);
    if($humidity == "on")
    {
        $humidity = 1;
    }
    else
    {
        $humidity = 0;
    }
}
else
{
    $humidity = 0;
}
if(!empty(trim($_POST["input-press"]))){
    $pressure = trim($_POST["input-press"]);
    if($pressure == "on")
    {
        $pressure = 1;
    }
    else
    {
        $pressure = 0;
    }
}
else
{
    $pressure = 0;
}
if(!empty(trim($_POST["input-pm2"]))){
    $pm = trim($_POST["input-pm2"]);
    if($pm == "on")
    {
        $pm = 1;
    }
    else
    {
        $pm = 0;
    }
}
else
{
    $pm = 0;
}
if(!empty(trim($_POST["latitude"]))){
    $latitude = trim($_POST["latitude"]);
}
else
{
    $empty = true;
}
if(!empty(trim($_POST["longitude"]))){
    $longitude = trim($_POST["longitude"]);
}
else
{
    $empty = true;
}
if(!empty(trim($_POST["altitude"]))){
    $height = trim($_POST["altitude"]);
}
else
{
    $empty = true;
}
if(!empty(trim($_POST["sensor_id"]))){
    $id_sensor = trim($_POST["sensor_id"]);
}
else
{
    $empty = true;
}


$my_devices = array(
    'type'      => 'my_devices',
    'features'  => array()
 );


 if(empty($empty))
 {
    echo "tak";
 }


if(isset($_SESSION['username']) && !empty($_SESSION['username']) && empty($empty)){
    $sql = "SELECT l.`id_location`, l.`latitude`, l.`longitude`, l.`height` FROM `device` d LEFT JOIN location l ON l.`id_location` = d.`id_location` WHERE `id_user` = :id_user AND `id_station` = :id_station"; 
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(':id_user', $param_id, PDO::PARAM_STR);
        $stmt->bindParam(':id_station', $param_id_station, PDO::PARAM_STR);
        echo $_SESSION['id_user'];
        // Set parameters
        $param_id = $_SESSION['id_user'];
        $param_id_station = $id_sensor;
        if($stmt->execute()){
            while($row = $stmt->fetch()) {
                $database_latitude = $row['latitude'];
                $database_longitude = $row['longitude'];
                $database_height = $row['height'];
                $id_locattion = $row['id_location'];
            }
            echo $database_latitude;
            echo $latitude;
            if($database_latitude != $latitude || $database_longitude != $longitude || $database_height != $height) 
            {               
                $sql = "INSERT INTO `location`(`latitude`,`longitude`,`height`,`country`,`zipcode`,`city`) VALUES ($latitude,$longitude,$height,\"not set\",\"not set\",\"not set\")"; 
                if($stmt = $pdo->prepare($sql)){
                    $stmt->execute();
                }
                else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
                $sql = "SELECT `id_location` FROM `location` ORDER BY `id_location` DESC LIMIT 1"; 
                if($stmt = $pdo->prepare($sql)){
                    if($stmt->execute()){
                        while($row = $stmt->fetch()) {
                            $id_locattion = $row['id_location'];
                        }
                    $sql = "UPDATE device SET `id_location` = :id_location , `Temperature`= :Temperature , `Humidity`= :Humidity , `Pressure`= :Pressure , `PM`= :PM WHERE `id_station`= :id_sensor"; 
                    unset($stmt);
                    
                    if($stmt = $pdo->prepare($sql)){
                        $stmt->bindParam(':id_location', $param_id_loc, PDO::PARAM_STR);
                        $stmt->bindParam(':Temperature', $param_Temperature, PDO::PARAM_STR);
                        $stmt->bindParam(':Humidity', $param_Humidity, PDO::PARAM_STR);
                        $stmt->bindParam(':Pressure', $param_Pressure, PDO::PARAM_STR);
                        $stmt->bindParam(':PM', $param_PM, PDO::PARAM_STR);
                        $stmt->bindParam(':id_sensor', $param_id_sensor, PDO::PARAM_STR);

                        $param_id_loc = (string)$id_locattion;
                        $param_Temperature = (string)$temperature;
                        $param_Humidity = (string)$humidity;
                        $param_Pressure = (string)$pressure;
                        $param_PM = (string)$pm;
                        $param_id_sensor = (string)$id_sensor;

                        $stmt -> debugDumpParams(); 
                        print_r($stmt);
                        $stmt->execute();
                    }
                    else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }   
                }
            }
                else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
            } 
    else{
                echo "Oops! Something went wrong. Please try again later.";
            }
    }
}

?>