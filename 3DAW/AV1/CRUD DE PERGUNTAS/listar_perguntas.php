<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Pergunta de Múltipla Escolha</title>
</head>
<body>
</html>

<?php
$arquivo = fopen("perguntas.txt", "r") or die("Erro ao abrir arquivo!");
echo "<h2>Lista de Perguntas</h2>";

echo "<table border='1' cellpadding='5' cellspacing='0'>";
echo "<tr><th>ID</th><th>Pergunta</th><th>Alternativas</th><th>Resposta Correta</th><th>Ações</th></tr>";

$f=fgets($arquivo);

while(($linha = fgets($arquivo)) !== false){
    if (trim($linha) == "") continue;

    $partes=explode("|", trim($linha));
    $id=$partes[0];
    $pergunta=$partes[1];
    $respostas=explode(";", $partes[2]);
    $correta=$partes[3];

    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$pergunta</td>";

    echo "<td>";
    $letras=array('A','B','C','D','E');
    for($i=0; $i<count($respostas); $i=$i+1){
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
fclose($arquivo);
?>
