<?php
require_once __DIR__.'/../base.php';
session_start();

$my_devices = array(
    'type'      => 'my_devices',
    'features'  => array()
 );
if(isset($_SESSION['username']) && !empty($_SESSION['username']) ){
    $sql = "SELECT d.`id_station`, l.`name`, l.`country`, l.`city`, l.`address` FROM `device` d LEFT JOIN location l ON l.`id_location` = d.`id_location` WHERE `id_user` = :id_user ORDER BY d.`id_station`"; 
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(':id_user', $param_id, PDO::PARAM_STR);
        
        // Set parameters
        $param_id = $_SESSION['id_user'];
        if($stmt->execute()){
            while($row = $stmt->fetch()) {
                $properties = array(
                        'id_station' => $row['id_station'],
                        'name_location' => $row['name'],
                        'country' => $row['country'], 
                        'city' => $row['city'], 
                        'address' => $row['address']
                    );
                array_push($my_devices['features'], $properties);
            }
            header('Content-type: application/json');
            echo json_encode($my_devices, JSON_NUMERIC_CHECK);
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}

?>