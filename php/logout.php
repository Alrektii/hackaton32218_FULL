<?php
// Unset session var

session_start();
unset($_SESSION['User_id']);
unset($_SESSION['Username']);


 
// ophalen session parameters 
$params = session_get_cookie_params();
 
// verwijderen van sessie cookie 
setcookie(session_name(),
        '', time() - 42000, 
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]);
 
// Destroy session 
session_destroy();

// Header refresh naar homepage
echo json_encode(true);
?>