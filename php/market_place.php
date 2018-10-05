<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Brabant 2 Go</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/second.css">
</head>
<body>
<span class="goBack" onclick="goBack()">Back</span>
<script>
    function goBack() {
        window.history.back();
    }
</script>
<?php
session_start();
include ("../config.php");
include ("../functions.php");
$pdo = ConnectDB();
if(!isset($_SESSION['Username'])){
    ?>
    <script>
        $(document).ready(function () {
            $(".login_form").fadeIn(0);
            $(".home").fadeOut(0);
        })
    </script>
    <?php
}
include ("../fields/forms.php");
?>
<script>
    // Action listeners
    $("#login").on("click" , function(e){
        e.preventDefault();
        var password = $("#pw").val();
        var username = $("#us").val();
        $.ajax({
            url: "login.php", //the page containing php script
            type: 'post',
            dataType: 'json',
            data: {username: username, password: password},
            success: function (data) {
                if(data === true){
                    $(".login_form").fadeOut(0);
                    $(".home").fadeIn(0);
                    $("#print_results").html("");
                }else {
                    var error = "Incorrect username and password combination!";
                    $(".login_form").append(error);
                }
            },
            error: function(){
                console.log('ajax 2  Failed ' + username + " " + password);
            }
        });
    });
    $("#register").on("click" , function(e){
        e.preventDefault();
        $(".login_form").fadeOut(0);
        $(".regform").fadeIn(0);
    });
    $("#back").on("click" , function (e) {
        e.preventDefault();
        $(".regform").fadeOut(0);
        $(".login_form").fadeIn(0);
    });
    $("#registerSubmit").on("click" , function(e){
        e.preventDefault();
        var password = $("#regpw").val();
        var username = $("#regus").val();
        var repassword = $("#regrepw").val();
        var email = $("#regemail").val();
        var sector = $("#sector").val();


        if(password === repassword){

            $.ajax({
                url: "php/Register.php", //the page containing php script
                type: 'post',
                dataType: 'json',
                data: {username: username, password: password , email: email , sector: sector},
                success: function (data) {
                    if (data === 'email false'){
                        $(".regform").append('The entered Email adress is not a valid one!');
                    }else if (data === true){
                        $(".regform").fadeOut(0);
                        $(".login_form").fadeIn(0).append("Succesfully registered!");

                    }
                },
                error: function(){
                    console.log('ajax 2  Failed ' + username + " " + password);
                }
            });
        } else {
            console.log(password + " " + repassword);
        }
    });
    $("#logout").on("click" , function(e){
        e.preventDefault();
        $.ajax({
            url: "logout.php",
            type: "post",
            dataType: "json",
            success: function (data) {
                if(data === true){
                    $(".home").fadeOut(0);
                    $(".login_form").fadeIn(0);
                }
            } ,
            error: function () {

            }
        });
    });
    $("#upload_new_product").on("click" , function (e) {
        e.preventDefault();
        $(".home").fadeOut(0);
        $(".new_product_form").fadeIn(0);
    });
    $("#upload_product_back").on("click" , function (e) {
        e.preventDefault();
        $(".new_product_form").fadeOut(0);
        $(".home").fadeIn(0);
        $("#print_results").html("");
    });
    $("#upload_product").on("click" , function (e) {
        e.preventDefault();
        handleFileSelect();
    });
    $("#upload_product_load").on("click" , function (e) {
        e.preventDefault();
        $("#print_results").html("");
        $.ajax({
            url: "load.php",
            type: "post",
            dataType: "json",
            data: {},
            success: function (data) {
                var lenght = data.length;
                console.log(lenght);
                var i = 0;

                while (i < lenght){

                    $("#print_results").append("<div class='p-results'><h1> " + data[i][2] + "</h1><img src='"+ data[i][3] +"' class='p-results-img' draggable='false'><div class='p-results-txt'>" +data[i][1] + "</div></div> <br/>");

                    //console.log("<img src='" + data[i][3] + "' >");
                    i++;
                }
            } ,
            error: function () {
                console.log("Error");
            }
        });
    });



    function handleFileSelect(){
        if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
            alert('The File APIs are not fully supported in this browser.');
            return;
        }

        input = document.getElementById('product_image');
        if (!input) {
            alert("Um, couldn't find the fileinput element.");
        }
        else if (!input.files) {
            alert("This browser doesn't seem to support the `files` property of file inputs.");
        }
        else if (!input.files[0]) {
            alert("Please select a file before clicking 'Load'");
        }
        else {
            file = input.files[0];
            fr = new FileReader();
            fr.onload = receivedText;
            //fr.readAsText(file);
            fr.readAsDataURL(file);
        }
    }
    function receivedText(){
        $("#image").attr("src" , fr.result);
        var desc = $("#product_desc").val();
        var title = $("#product_name").val();
        $.ajax({
            url: "upload.php",
            type: "post",
            dataType: "json",
            data: {desc: desc , name: title , img: fr.result},
            success: function (data) {
                if(data === true){
                    $("#product_desc").val("");
                    $("#product_name").val("");
                    $("#product_image").val("");
                    desc = null;
                    title = null;

                    $(".new_product_form").fadeOut(0);
                    $(".home").fadeIn(0);
                    $("#print_results").html("");
                }
            } ,
            error: function () {
                console.log("Error");
            }
        });
    }


</script>
</body>
</html>