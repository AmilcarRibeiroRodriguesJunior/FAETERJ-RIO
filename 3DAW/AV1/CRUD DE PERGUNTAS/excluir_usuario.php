<?php
$arquivo="usuarios.txt";
$id=$_GET['id'];
$usuarios=file($arquivo);

if(!isset($_GET['id'])){
    echo "ID não especificado.";
    exit;
}

$id=$_GET['id'];

$novoConteudo = "";
foreach($usuarios as $linha){
    $dados=explode(";", trim($linha));
    if($dados[0] != $id){
        $novoConteudo .= $linha;
    }
}
file_put_contents($arquivo, $novoConteudo);

$mensagem="Usuário excluído com sucesso!";
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Usuário</title>
</head>
<body>
    <h1><?= $mensagem ?></h1>
    <a href="listar_usuario.php">Voltar para a lista</a>
</body>
</html>
