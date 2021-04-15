<?php

include '../bd/conexao.php';
$con = bancoMysqli();
session_start();
$p = $_SESSION['chat'];

$nome = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);




$query1 = "INSERT INTO `comentario` (`protocolo`, `comentario`, `nome`) 
VALUES ('$p', '$nome', '$email')";
mysqli_query($con, $query1);

if(mysqli_insert_id($con)){
    echo true;
}else{
    echo false;
}

?>

