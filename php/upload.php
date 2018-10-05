<?php

if(isset($_POST['desc']) && isset($_POST['name']) && isset($_POST['img'])){

    include ("../config.php");
    include ('../functions.php');
    $pdo = ConnectDB();
    $parameters = array(':desc' => $_POST['desc'] , ':title' => $_POST['name'] , ':img' => $_POST['img']);
    $sth = $pdo->prepare("INSERT INTO `producten`( `productDesc`, `productTitle`, `productImg`) VALUES (:desc, :title, :img)");
    $sth->execute($parameters);

    echo json_encode(true);
}else {
    echo json_encode(false);
}



