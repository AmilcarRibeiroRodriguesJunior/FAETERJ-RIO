<?php
$arquivo="usuarios.txt";
$usuarios=[];

$usuarios=array();
if(file_exists($arquivo)){
    $linhas=file($arquivo);
    $primeira=true;
    foreach($linhas as $linha){
        $linha=trim($linha);
        if($linha == "") continue;

        if($primeira){
            $primeira=false; 
            continue;
        }

        $usuarios[]=explode(";", $linha);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuários</title>
</head>
<body>
    <h1>Usuários Cadastrados</h1>
    <a href="cadastrar_usuario.php">Cadastrar novo usuário</a><br><br>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
        <?php foreach ($usuarios as $usuario){ ?>
        <tr>
            <td><?= $usuario[0] ?></td>
            <td><?= $usuario[1] ?></td>
            <td><?= $usuario[2] ?></td>
            <td>
                <a href="alterar_usuario.php?id=<?= $usuario[0] ?>">Alterar</a> | 
                <a href="excluir_usuario.php?id=<?= $usuario[0] ?>" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</body>
</html>
