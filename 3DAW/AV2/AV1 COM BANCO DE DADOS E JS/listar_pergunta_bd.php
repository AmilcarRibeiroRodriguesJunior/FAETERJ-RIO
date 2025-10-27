<?php
$servidor="localhost";
$username="root";
$senha="";
$database="3DAW-AR";

$conn=new mysqli($servidor, $username, $senha, $database);

if($conn->connect_error){
    die(json_encode("Erro de conexão com o banco."));
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $id=0;
    if(isset($_POST["id"])){
        $id = intval($_POST["id"]);
    }

    if($id <= 0){
        echo json_encode("ID inválido!");
        exit;
    }

    $sql="SELECT * FROM Perguntas WHERE id = " . $id;
    $resultado = $conn->query($sql);

    if($resultado && $resultado->num_rows > 0){
        $linha = $resultado->fetch_assoc();
        echo json_encode($linha, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode("Nenhuma pergunta encontrada para o ID informado.");
    }
}

$conn->close();
?>
