<?php
$host="localhost";
$user="root";
$pass="";
$db="escola";

$conn=new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
    die("Erro na conexão: " . $conn->connect_error);
}

if(isset($_POST['id'])){
    $id=intval($_POST['id']);
    $sql="DELETE FROM alunos WHERE id = $id";

    if($conn->query($sql) === TRUE){
        echo "OK";
    } else {
        echo "Erro: " . $conn->error;
    }
} else {
    echo "ID não informado";
}
?>
