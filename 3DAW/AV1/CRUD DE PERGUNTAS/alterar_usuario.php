<?php
$arquivo="usuarios.txt";
$usuarios=file($arquivo);
$usuarioEncontrado=null;
$mensagem="";

if(!isset($_GET['id'])){
    echo "ID não especificado.";
    exit;
}

$id=$_GET['id'];

foreach($usuarios as $linha){
    $dados = explode(";", trim($linha));
    if($dados[0] == $id){
        $usuarioEncontrado=$dados;
        break;
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $novoNome=$_POST['nome'];
    $novoEmail=$_POST['email'];

    $novoConteudo = "";
    foreach($usuarios as $linha){
        $dados=explode(";", trim($linha));
        if($dados[0] == $id){
            $novoConteudo .= "$id;$novoNome;$novoEmail\n";
        } else {
            $novoConteudo .= $linha;
        }
    }
    file_put_contents($arquivo, $novoConteudo);
    $mensagem="Usuário alterado com sucesso!";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Usuário</title>
</head>
<body>
    <h1>Alterar Usuário</h1>

    <?php if ($mensagem != ""){ ?>
        <p><?php echo $mensagem; ?></p>
        <a href="listar_usuario.php">Voltar para a lista</a>
    <?php } else { ?>
        <form method="post">
            Nome: <input type="text" name="nome" value="<?= $usuarioEncontrado[1] ?>"><br><br>
            Email: <input type="email" name="email" value="<?= $usuarioEncontrado[2] ?>"><br><br>
            <input type="submit" value="Salvar Alterações">
        </form>
    <?php } ?>
</body>
</html>
