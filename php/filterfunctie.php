<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "hackathon_3/20/18";
$debug_message = "true";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
// print the try error
catch(PDOException $error) {
}


// verschilende gemeentes push in variable string gemeente_Count
$sth = $pdo->prepare("SELECT COUNT(DISTINCT gemeenteID) AS gemeente_Count FROM gemeentecijfers");
$sth->execute();
$gemeente_Count = $sth->fetch();
$gemeente_Count = $gemeente_Count[0];

// make gemeente punten array $gemeente_array
$gemeente_array = array();
$sth = $pdo->prepare("SELECT * FROM `gemeente`");
$sth->execute();
while ($result = $sth->fetch()) {
    $gemeente_array[$result['gemeenteID']]['punten'] = 0;
    $gemeente_array[$result['gemeenteID']]['naam'] = $result['gemeenteNaam'];
    $gemeente_array[$result['gemeenteID']]['concurentie'] = rand(3, 20);
    $gemeente_array[$result['gemeenteID']]['ID'] = $result['gemeenteID'];

}
// check if voorzieningen are true
if(isset($_POST['filter_one'])){

    $voorziening_one_check = $_POST['filter_one'];
    $voorziening_two_check = $_POST['filter_two'];
    $voorziening_three_check = $_POST['filter_three'];
    $voorziening_four_check = $_POST['filter_four'];
    $voorziening_five_check = $_POST['filter_five'];
    $voorziening_six_check = $_POST['filter_six'];
    $voorziening_seven_check = $_POST['filter_seven'];
    $voorziening_eight_check = $_POST['filter_eight'];
    $voorziening_nine_check = $_POST['filter_nine'];
    $voorziening_ten_check = $_POST['filter_ten'];
    $voorziening_eleven_check = $_POST['filter_eleven'];

}
else{
    $voorziening_one_check = $voorziening_two_check = $voorziening_three_check = $voorziening_four_check = False;;
    $voorziening_five_check = $voorziening_six_check = $voorziening_seven_check = $voorziening_eight_check = False;
    $voorziening_nine_check = $voorziening_ten_check = $voorziening_eleven_check = False;
}



$voorziening_one = ($voorziening_one_check == 'true')? 1: 1001;
$voorziening_two = ($voorziening_two_check == 'true')? 2: 1002;
$voorziening_three = ($voorziening_three_check == 'true')? 3: 1003;
$voorziening_four = ($voorziening_four_check == 'true')? 4: 1004;
$voorziening_five = ($voorziening_five_check == 'true')? 5: 1005;
$voorziening_six = ($voorziening_six_check == 'true')? 6: 1006;
$voorziening_seven = ($voorziening_seven_check == 'true')? 7: 1007;
$voorziening_eight = ($voorziening_eight_check == 'true')? 8: 1008;
$voorziening_nine = ($voorziening_nine_check == 'true')? 9: 1009;
$voorziening_ten = ($voorziening_ten_check == 'true')? 10: 1010;
$voorziening_eleven = ($voorziening_eleven_check == 'true')? 11: 1011;



$querry = "SELECT * FROM `gemeentecijfers` WHERE `voorzieningID` in ('$voorziening_one',
                                                                     '$voorziening_two',
                                                                     '$voorziening_three',
                                                                     '$voorziening_four',
                                                                     '$voorziening_five',
                                                                     '$voorziening_six',
                                                                     '$voorziening_seven',
                                                                     '$voorziening_eight',
                                                                     '$voorziening_nine',
                                                                     '$voorziening_ten',
                                                                     '$voorziening_eleven')";
// push voorzieningen in gemeente array punten
$sth = $pdo->prepare($querry);
$sth->execute();
while ($result = $sth->fetch()){
    $gemeente_array[$result['gemeenteID']]['punten'] += $result['gemeenteCijfer'];
}
rsort($gemeente_array);
 echo json_encode($gemeente_array);




