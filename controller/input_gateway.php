<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
  
$db = new PDO('mysql:host=localhost;dbname=rsp;charset=utf8mb4', 'esp2', 'pomiarowiec');

	 
echo "lol";
// $packet_raw = $_GET['packet_raw'];
// $packet_raw = addslashes($packet_raw);
// $temperature = $_GET['temperature'];
// $temperature = addslashes($temperature);
// $humidity = $_GET['humidity'];
// $humidity = addslashes($humidity);
// $pressure = $_GET['pressure'];
// $pressure = addslashes($pressure);
// $pm2 = $_GET['pm2']; 
// $pm2 = addslashes($pm2);
// $pm10 = $_GET['pm10'];
// $pm10 = addslashes($pm10);
// $proximity = $_GET['proximity'];
// $proximity = addslashes($proximity);

$data_recived = file_get_contents('php://input');
$st = json_decode($data_recived);

foreach($st->gateway_recive as $data_frame)
{
  echo $data_frame->temperature;
  echo "<br>";
  echo "INSERT INTO `measurment`(`id_station`,`temperature`,`humidity`,`pressure`,`pm2`,`pm10`) VALUES ($data_frame->id_device,$data_frame->temperature,$data_frame->humidity,$data_frame->pressure,$data_frame->pm2,$data_frame->pm10)";
  $stmt = $db->query("INSERT INTO `measurment`(`id_station`,`temperature`,`humidity`,`pressure`,`pm2`,`pm10`) VALUES ($data_frame->id_device,$data_frame->temperature,$data_frame->humidity,$data_frame->pressure,$data_frame->pm2,$data_frame->pm10)");
}

  $stmt = $db->query("SELECT * FROM `measurment` ORDER BY `measurment`.`id_measure` DESC LIMIT 1 ");
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['temperature'].' '.$row['humidity']; //etc...
}
  $row_count = $stmt->rowCount();
  echo $row_count.' rows selected';


?>