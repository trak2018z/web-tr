<?php
require_once __DIR__.'/../base.php';

$json_table = array(
    'type'      => 'table',
    'features'  => array()
 );

$sql = "SELECT  d.`id_station`, d.`id_location`, m.`date_measure`, m.`temperature`, m.`humidity`, m.`pressure`, m.`pm10` , l.`id_location`, l.`city` FROM `measurment`m INNER JOIN (SELECT  MAX(m.`id_measure`) as `id`,m.`id_station` FROM `measurment` m GROUP BY m.`id_station`) maxx ON m.`id_measure` = maxx.`id` RIGHT JOIN `device` d ON maxx.`id_station` = d.`id_station` LEFT JOIN `location` l ON d.`id_location` = l.`id_location`";
if($stmt = $pdo->prepare($sql)){

    // Set parameters
    if($stmt->execute()){
        while($row = $stmt->fetch()) {
            $properties = array(
                    'id_station' => $row['id_station'],
                    'date_measure' => $row['date_measure'],
                    'temperature' => $row['temperature'], 
                    'humidity' => $row['humidity'], 
                    'pressure' => $row['pressure'], 
                    'pm10' => $row['pm10'],
                    'city' => $row['city'],
                );
            array_push($json_table['features'], $properties);
        }
        header('Content-type: application/json');
        echo json_encode($json_table, JSON_NUMERIC_CHECK);
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}


?>