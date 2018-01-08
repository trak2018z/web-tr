
<?php
error_reporting(E_ALL); 
try{
    $pdo = new PDO('mysql:host=localhost;dbname=rsp;charset=utf8mb4', 'esp2', 'pomiarowiec');
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>