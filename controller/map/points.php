<?php

require_once __DIR__.'/../base.php';

$geojson = array(
    'type'      => 'Sensor',
    'features'  => array()
 );

$sql = "SELECT l.`id_location`,l.`latitude`,l.`longitude` FROM `location` l RIGHT JOIN `device`d ON l.`id_location`= d.`id_location`";
if($stmt = $pdo->prepare($sql)){
    // Attempt to execute the prepared statement
    if($stmt->execute()){
        while($row = $stmt->fetch()) {
            $feature = array(
                'id' => $row['id_location'],
                'type' => 'Feature', 
                'geometry' => array(
                    'type' => 'Point',
                    # Pass Longitude and Latitude Columns here
                    'coordinates' => array($row['latitude'], $row['longitude'])
                ),
                # Pass other attribute columns here
                'properties' => array(
                    'name' => $row['Name']
                    // 'description' => $row['Description'],
                    // 'sector' => $row['Sector'],
                    // 'country' => $row['Country'],
                    // 'status' => $row['Status'],
                    // 'start_date' => $row['Start Date'],
                    // 'end_date' => $row['End Date'],
                    // 'total_invest' => $row['Total Lifetime Investment'],
                    // 'usg_invest' => $row['USG Investment'],
                    // 'non_usg_invest' => $row['Non-USG Investment']
                    )
                );
            # Add feature arrays to feature collection array
            array_push($geojson['features'], $feature);
        }
        
        header('Content-type: application/json');
        echo json_encode($geojson, JSON_NUMERIC_CHECK);
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}
unset($stmt);
unset($pdo);

?>