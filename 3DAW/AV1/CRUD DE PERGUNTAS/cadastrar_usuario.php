<?php
$arquivo="usuarios.txt";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome=$_POST['nome'];
    $email=$_POST['email'];

    $id=1;
    if(file_exists($arquivo)){
        $linhas = file($arquivo);
        if(count($linhas) > 0){
            $ultima=trim(end($linhas));
            $dados=explode(";", $ultima);
            $id=$dados[0] + 1;
        }
    }

    $linha="$id;$nome;$email\n";
    file_put_contents($arquivo, $linha, FILE_APPEND);

    echo "<h2>Usuário cadastrado com sucesso!</h2>";
    echo "<a href='listar_usuario.php'>Ver lista de usuários</a>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
</head>
<body>
    <h1>Cadastrar Usuário</h1>
    <form method="post">
        Nome: <input type="text" name="nome" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        <input type="submit" value="Cadastrar">
    </form>
    <br>
</body>
</html>