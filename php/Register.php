<?php
session_start();

function Register($Username , $Password , $Email, $sector){
    require ('../functions.php');
    require ('../config.php');
    $pdo = ConnectDB();

    if(is_email($Email)){
        $Password = hash('sha512', $Password);

        $parameters = array(':firstname' => $Username,
            ':password' => $Password, ':email' => $Email , ':sector' => $sector);

        $sth = $pdo->prepare('INSERT INTO tbl_users (userName, password , userEmail, userSector) 
                              VALUES (:firstname, :password , :email ,:sector )');

        $sth->execute($parameters);
        return true;
    }else {
        return 'email false';
    }


}

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {

    $Register = Register($_POST['username'] , $_POST['password'] , $_POST['email'] , $_POST['sector']);
    echo json_encode($Register);
}

?>
