<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo - Sistema de Perguntas</title>
</head>
<body>
</html>
<h1>Sistema de Perguntas - Administração</h1>

<h2>Usuário</h2>
<a href="cadastrar_usuario.php">Cadastrar Novo Usuário</a>
<br><br>

<hr>

<h2>Perguntas de Múltipla Escolha</h2>
<a href="criar_pergunta.php">Criar Nova Pergunta de Múltipla Escolha</a>
<br><br>

<?php
$arquivo = "perguntas.txt";
if(file_exists($arquivo)){
    $fp = fopen($arquivo, "r") or die("Erro ao abrir arquivo!");
    fgets($fp);

    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Pergunta</th><th>Alternativas</th><th>Resposta Correta</th><th>Ações</th></tr>";

    while (($linha = fgets($fp)) !== false) {
        if (trim($linha) == "") continue;
        $partes = explode("|", trim($linha));
        $id = $partes[0];
        $pergunta = $partes[1];
        $respostas = explode(";", $partes[2]);
        $correta = $partes[3];

        echo "<tr>";
        echo "<td>$id</td>";
        echo "<td>$pergunta</td>";
        echo "<td>";
        $letras = array('A','B','C','D','E');
        for ($i=0; $i<count($respostas); $i=$i+1) {
            echo $letras[$i].": ".$respostas[$i]."<br>";
        }
        echo "</td>";
        echo "<td>$correta</td>";
        echo "<td>";
        echo "<a href='alterar_pergunta.php?id=$id'>Alterar</a> | ";
        echo "<a href='excluir_pergunta.php?id=$id'>Excluir</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    fclose($fp);
} else {
    echo "<p>Nenhuma pergunta de múltipla escolha cadastrada.</p>";
}
?>

<hr>

<h2>Perguntas de Texto</h2>
<a href="criar_pergunta_texto.php">Criar Nova Pergunta de Texto</a>
<br><br>

<?php
$arquivo = "perguntas_texto.txt";
if (file_exists($arquivo)) {
    $fp = fopen($arquivo, "r") or die("Erro ao abrir arquivo!");
    fgets($fp);

    echo "<table border='1' cellpadding='5' cellspacing='0'>";
    echo "<tr><th>ID</th><th>Pergunta</th><th>Resposta</th><th>Ações</th></tr>";

    while (($linha = fgets($fp)) !== false) {
        if (trim($linha) == "") continue;
        $partes = explode("|", trim($linha));
        $id = $partes[0];
        $pergunta = $partes[1];
        $resposta = $partes[2];

        echo "<tr>";
        echo "<td>$id</td>";
        echo "<td>$pergunta</td>";
        echo "<td>$resposta</td>";
        echo "<td>";
        echo "<a href='alterar_pergunta_texto.php?id=$id'>Alterar</a> | ";
        echo "<a href='excluir_pergunta_texto.php?id=$id'>Excluir</a>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    fclose($fp);
} else {
    echo "<p>Nenhuma pergunta de texto cadastrada.</p>";
}
?>
