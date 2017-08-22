<?php
    $host="localhost";
    $user="root";
    $pass="";
    $db="benfeitoria_bd";
	$conexao=mysqli_connect ($host, $user, $pass);
 	mysqli_select_db ($conexao, $db);
?>