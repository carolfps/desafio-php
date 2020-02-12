<?php 
if(isset($_POST["encerrar"])){
    session_start();
    session_unset();
    session_destroy();
    header("Location: /desafio-php/login.php");
}?>