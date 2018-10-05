<?php


function ConnectDB()
{
    try{
        $pdo = new PDO("mysql:host=".HOST.";dbname=".DBNAME,USERNAME,PASSWORD);
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }

    return $pdo;
}
function LoginCheck($pdo)
{
    // Controleren of Sessie variabelen bestaan
    if (isset($_SESSION['user_id'], $_SESSION['username'],$_SESSION['login_string']))
    {
        $KlantID = $_SESSION['user_id'];
        $Login_String = $_SESSION['login_string'];
        $Username = $_SESSION['username'];

        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        $parameters = array(':KlantID'=>$KlantID);
        $sth = $pdo->prepare('SELECT password FROM Users WHERE userID = :KlantID LIMIT 1');

        $sth->execute($parameters);

        // controleren of de klant voorkomt in de DB
        if ($sth->rowCount() == 1)
        {
            // Variabelen inlezen uit query
            $row = $sth->fetch();

            //check maken
            $Login_Check = hash('sha512', $row['Password'] . $user_browser);

            //controleren of check overeenkomt met sessie
            if ($Login_Check == $Login_String)
                return true;
            else
                return false;
        } else
            return false;
    } else
        return false;
}
function is_email($Invoer)
{
    return (bool)(preg_match("^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$^",$Invoer));
}


/** Controleert of een string aan de minimum lengte voldoet
 * @return  boolean
 */
function is_minlength($Invoer, $MinLengte)
{
    return (strlen($Invoer) >= (int)$MinLengte);
}

/** Controleert of invoer een NL postcode is
 * @return  boolean
 */
function is_NL_PostalCode($Invoer)
{
    return (bool)(preg_match('#^[1-9][0-9]{3}\h*[A-Z]{2}$#i', $Invoer));
}

/** Controleert of invoer een NL telefoonnr is
 * @return  boolean
 */
function is_NL_Telnr($Invoer)
{
    return (bool)(preg_match('#^0[1-9][0-9]{0,2}-?[1-9][0-9]{5,7}$#', $Invoer)
        && (strlen(str_replace(array('-', ' '), '', $Invoer)) == 10));
}


/** Controleert of invoer alleen uit letters bestaat
 * @return  boolean
 */
function is_Char_Only($Invoer)
{
    return (bool)(preg_match("/^[a-zA-Z ]*$/", $Invoer)) ;
}

/** functie die controleert of een gebruikersnaam wel of niet in de database		  * voorkomt.
 */
function is_Username_Unique($Invoer,$pdo)
{
    $parameters = array(':Username'=>$Invoer);
    $sth = $pdo->prepare('SELECT userID FROM users WHERE username = :Username LIMIT 1');

    $sth->execute($parameters);

// controleren of de username voorkomt in de DB
    if ($sth->rowCount() == 1)
        return false;//username komt voor
    else
        return true;//username komt niet voor
}
