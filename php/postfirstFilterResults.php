<?php


include ('../config.php');
include ('../functions.php');
$pdo = ConnectDB();

$resultarray = array();
$resultArrayStad = array();
$parameters = array();
$sth= $pdo->prepare('SELECT * FROM gemeente');
$sth->execute($parameters);
while($row = $sth->fetch()) {
    $parameters = array(':gemId' => $row['gemeenteID']);
    $sth2 = $pdo->prepare('SELECT * FROM gemeentecijfers WHERE gemeenteID = :gemId ORDER BY gemeenteCijfer ASC LIMIT 3');
    $sth2->execute($parameters);
    while ($row2 = $sth2->fetch()) {
        $parameters = array(':voorzieningid' => $row2['voorzieningID']);
        $sth3 = $pdo->prepare('SELECT * FROM voorziening WHERE voorzieningID = :voorzieningid ORDER BY voorzieningID ASC');
        $sth3->execute($parameters);

        if ($rij = $sth3->fetch()) {
            array_push($resultArrayStad, $rij['voorzieningNaam']);
        }
    }
    $parameters = array(':id' => $row['gemeenteID']);
    $sth4 = $pdo->prepare('SELECT * FROM gemeente WHERE gemeenteID = :id ORDER BY gemeenteID ASC');
    $sth4->execute($parameters);
    if ($row4 = $sth4->fetch()) {
        array_push($resultArrayStad, $row4['gemeenteImg']);
    }


    array_push($resultArrayStad, $row['gemeenteID']);
    array_push($resultArrayStad, $row['gemeenteNaam']);
    array_push($resultarray, $resultArrayStad);
    $resultArrayStad = array();
}

echo json_encode($resultarray);