<?php

session_start();
ob_start();
require ('../functions.php');
require ('../config.php');
$pdo = ConnectDB();

function login($Username , $Password , $pdo)
{
    $parameter = array(':status'=>$Username);
    $sth = $pdo->prepare('SELECT * FROM tbl_users WHERE userName = :status');

    $sth->execute($parameter);

    if ($sth->rowCount() == 1)
    {
        // Variabelen inlezen uit query
        $row = $sth->fetch();

        //   Salt en hash code onzetten naar variable
        $Password = hash('sha512', $Password);



        if ($row['password'] == $Password)
        {
            $user_browser = $_SERVER['HTTP_USER_AGENT'];

            $_SESSION['User_id'] = $row['userId'];
            $_SESSION['Username'] = $row['userName'];

            // Login succes.
            return true;
        }
        else
        {
            // password wrong
            return false;
        }
    }
    else
    {
        // username does not exist
        return false;
    }
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];



   $check = login($username,$password,$pdo);

    if($check){
        echo json_encode($check);
    }
    else{
        echo json_encode($check);
    }

}

?>