<?php
$servidor="localhost";
$username="root";
$senha="";
$database="3DAW-AR";

$conn=new mysqli($servidor, $username, $senha, $database);
if($conn->connect_error){
    die(json_encode("Conexao falhou"));
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $id=$_POST["id"];
    $sql="DELETE FROM Perguntas WHERE id=$id";
    $res=$conn->query($sql);

    if($res){
        echo json_encode("Pergunta excluída com sucesso!");
    } else {
        echo json_encode("Erro ao excluir a pergunta!");
    }
}
?>
