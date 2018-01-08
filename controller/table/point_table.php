<?php
require_once __DIR__.'/../base.php';

if(empty(trim($_GET['id_sensor']))){
    $point_err = 'Bad id.';
    
}
else{
    $id_sensor = trim($_GET['id_sensor']);
}
$json_table = array(
    'type'      => 'table',
    'features'  => array()
 );

if(!empty($id_sensor))
{
    $sql = "SELECT `date_measure`,`temperature`,`humidity`,`pressure`,`pm2` FROM measurment WHERE id_station = :id_sensor  ORDER BY `id_measure` DESC LIMIT 50";
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(':id_sensor', $param_id_sensor, PDO::PARAM_STR);
        
        // Set parameters
        $param_id_sensor = $id_sensor;
        if($stmt->execute()){
            while($row = $stmt->fetch()) {
                $properties = array(
                        'date_time' => $row['date_measure'],
                        'type' => 'sensor 1',
                        'temperature' => $row['temperature'], 
                        'humidity' => $row['humidity'], 
                        'pressure' => $row['pressure'], 
                        'pm2' => $row['pm2']
                    );
                array_push($json_table['features'], $properties);
            }
            header('Content-type: application/json');
            echo json_encode($json_table, JSON_NUMERIC_CHECK);
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
}

?>