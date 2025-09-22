<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Pergunta de Texto</title>
</head>
<body>
</html>

<?php
$arquivo = fopen("perguntas_texto.txt", "r") or die("Erro ao abrir arquivo!");
echo "<h2>Lista de Perguntas de Texto</h2>";

echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>ID</th><th>Pergunta</th><th>Resposta</th><th>Ações</th></tr>";

$f=fgets($arquivo);

while(($linha = fgets($arquivo)) !== false){
    if(trim($linha) == "") continue;

    $partes=explode("|", trim($linha));
    $id=$partes[0];
    $pergunta=$partes[1];
    $resposta=$partes[2];

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
fclose($arquivo);
?>
