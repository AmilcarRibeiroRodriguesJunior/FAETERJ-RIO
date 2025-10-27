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
    $pergunta=$_POST["pergunta"];
    $tipo=$_POST["tipo"];
    $assunto=$_POST["assunto"];

    $sql="UPDATE Perguntas SET pergunta='$pergunta', tipo=$tipo, assunto='$assunto' WHERE id=$id";
    $res=$conn->query($sql);

    if($res){
        echo json_encode("Pergunta alterada com sucesso!");
    } else {
        echo json_encode("Erro ao alterar a pergunta!");
    }
}
?>
