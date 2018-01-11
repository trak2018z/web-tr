<?php
// Initialize the session
session_start();



// If session variable is not set it will redirect to login page
if(isset($_SESSION['username']) && !empty($_SESSION['username'])){
$user_name = $_SESSION['username'];
$login = true;
}
else
{
    $user_name = "";
    $login = false;
}

$user_json = array(
    'logged'      => $login,
    'user_name'  => (string)$user_name
 );
 echo json_encode($user_json);
?>
