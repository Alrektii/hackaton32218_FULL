<?php

    include ("../config.php");
    include ('../functions.php');
    $pdo = ConnectDB();

    $sth = $pdo->prepare('SELECT * FROM producten');
    $sth->execute();

    $queryresuls = array();
    while($row = $sth->fetch()){
        array_push($queryresuls , $row);
    }
    echo json_encode($queryresuls);