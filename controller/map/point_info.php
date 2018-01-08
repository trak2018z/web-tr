<?php
require_once __DIR__.'/../base.php';

if(empty(trim($_GET['id_sensor']))){
    $point_err = 'Bad id.';
    
}
else{
    $id_sensor = trim($_GET['id_sensor']);
}

if(!empty($id_sensor))
{
    $sql = "SELECT `date_measure`,`temperature`,`humidity`,`pressure`,`pm2` FROM measurment WHERE id_station = :id_sensor  ORDER BY `id_measure` DESC LIMIT 1";
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(':id_sensor', $param_id_sensor, PDO::PARAM_STR);
        
        // Set parameters
        $param_id_sensor = $id_sensor;
        if($stmt->execute()){
            while($row = $stmt->fetch()) {
                $properties = array(
                    'id' => $id_sensor,
                    'last_data' => $row['date_measure'], 
                    'value' => array(
                        'type' => 'sensor 1',
                        'temperature' => $row['temperature'], 
                        'humidity' => $row['humidity'], 
                        'pressure' => $row['pressure'], 
                        'pm2' => $row['pm2']
                    )
                    );

                header('Content-type: application/json');
                echo json_encode($properties, JSON_NUMERIC_CHECK);
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}

?>