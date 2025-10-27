<?php
$servidor = "localhost";
$username = "root";
$senha = "";
$database = "faeterj3dawmanha";

$conn = new mysqli($servidor, $username, $senha, $database);
if ($conn->connect_error) {
    die(json_encode("Conexao falhou"));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pergunta = $_POST["pergunta"];
    $tipo = $_POST["tipo"];
    $assunto = $_POST["assunto"];

    $sql = "INSERT INTO Perguntas (pergunta, tipo, assunto) VALUES ('$pergunta', $tipo, '$assunto')";
    $res = $conn->query($sql);

    if ($res) {
        echo json_encode("Pergunta incluída com sucesso!");
    } else {
        echo json_encode("Erro ao incluir a pergunta!");
    }
}
?>
