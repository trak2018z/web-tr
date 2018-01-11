<?php
require_once __DIR__.'/../base.php';
session_start();
if(empty(trim($_GET['id_sensor']))){
    $point_err = 'Bad id.';
}
else{
    $id_sensor = trim($_GET['id_sensor']);
}
$device_settings = array(
    'type'      => 'device_settings',
    'features'  => array()
 );
if(isset($_SESSION['username']) && !empty($_SESSION['username']) && !empty($id_sensor)){
    $sql = "SELECT d.*,l.`latitude`, l.`longitude`, l.`height` FROM `device` d LEFT JOIN location l ON l.`id_location` = d.`id_location` WHERE `id_user` = :id_user AND `id_station` = :id_station  ORDER BY d.`id_station`"; 
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(':id_user', $param_id, PDO::PARAM_STR);
        $stmt->bindParam(':id_station', $param_id_station, PDO::PARAM_STR);
        
        // Set parameters
        $param_id = $_SESSION['id_user'];
        $param_id_station = $id_sensor;
        if($stmt->execute()){
            while($row = $stmt->fetch()) {
                $properties = array(
                        'Temperature' => $row['Temperature'],
                        'Humidity' => $row['Humidity'],
                        'Pressure' => $row['Pressure'], 
                        'PM' => $row['PM'],
                        'latitude' => $row['latitude'], 
                        'longitude' => $row['longitude'],
                        'height' => $row['height']
                    );
                array_push($device_settings['features'], $properties);
            }
            header('Content-type: application/json');
            echo json_encode($device_settings, JSON_NUMERIC_CHECK);
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}

?>