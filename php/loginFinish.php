<html>
    <head>
        <?php
        session_start();
        ob_start();
        require ('../functions.php');
        require ('../config.php');
        $pdo = ConnectDB();
        ?>
        <meta charset="utf-8">
        <title>Loading...</title>
    </head>

    <body>
        <?php
        if(isset($_GET['Check'])){

            if($_GET['Check'] == 'succes'){

                echo '<h6>Authorization successfull</h6>';
                echo '<h6>Connecting to home...</h6>';

                header("Refresh:3; url=../");
            }elseif($_GET['Check'] == 'failure'){
                echo '<h1>Authorization failed</h1>';

                header("Refresh:3; url=../");
            }else{
                header('Location: ../');
            }
        }else{
            header('Location: ../');
        }
        ?>
    </body>

</html>