<?php

include("../config.php");
include("../functions.php");
$pdo = ConnectDB();



if(isset($_POST['gem1']) && isset($_POST['gem2'])){
    // POST gemeente ID's

    $gem1Array = array();
    $gem2Array = array();
    $returnArray = array();

    $vergelijkarray = array();

    $parameters = array(':gem1id' => $_POST['gem1']);
    $sth = $pdo->prepare('SELECT * FROM gemeentecijfers WHERE gemeenteID = :gem1id');
    $sth->execute($parameters);

    $i = 0;
    while ($row = $sth->fetch()){
        $cijfer[$i] = $row['gemeenteCijfer'];
        array_push($gem1Array, $cijfer[$i]);
        $i++;
    }
    $parameters = array(':gem2id' => $_POST['gem2']);
    $sth = $pdo->prepare('SELECT * FROM gemeentecijfers WHERE gemeenteID = :gem2id');
    $sth->execute($parameters);
    $i = 0;
    while ($row = $sth->fetch()){
        $cijfer[$i] = $row['gemeenteCijfer'];
        array_push($gem2Array, $cijfer[$i]);
        $i++;
    }

    array_push($returnArray , $gem1Array);
    array_push($returnArray, $gem2Array);

    echo json_encode($returnArray);






}






