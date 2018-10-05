<?php
include("../config.php");
include ("../functions.php");
$pdo = ConnectDB();

if(isset($_POST['data'])) {

    $gemID = $_POST['data'];
    $gemName = $_POST['name'];
    $resultarray = array();
    $parameters = array(':gemId' => $gemID);
    $sth = $pdo->prepare('SELECT * FROM gemeentecijfers WHERE gemeenteID = :gemId ORDER BY gemeenteCijfer ASC LIMIT 3');
    $sth->execute($parameters);
        while ($row = $sth->fetch()) {
            $parameters = array(':voorzieningid' => $row['voorzieningID']);
            $sth2 = $pdo->prepare('SELECT * FROM voorziening WHERE voorzieningID = :voorzieningid ORDER BY voorzieningID ASC');
            $sth2->execute($parameters);

            if ($rij = $sth2->fetch()) {
                array_push($resultarray , $rij['voorzieningNaam']);
            }
        }
        $parameters = array(':id' => $gemID);
        $sth = $pdo->prepare('SELECT * FROM gemeente WHERE gemeenteID = :id ORDER BY gemeenteID ASC');
        $sth->execute($parameters);
        if ($row = $sth->fetch()){
            array_push($resultarray , $row['gemeenteImg']);
        }
        array_push($resultarray , $gemID);
        array_push($resultarray , $gemName);
        echo json_encode($resultarray);

} else {
    echo json_encode('error data array');
}


