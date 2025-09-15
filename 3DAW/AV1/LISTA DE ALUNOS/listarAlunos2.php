<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de alunos</title>
</head>
<body>
    <h1>Lista de Alunos</h1>
    <table>
        <tr>
            <th>Matrícula</th>
            <th>Nome</th>
            <th>E-mail</th>
        </tr>
        <?php
        $arquivo = fopen("alunos.txt", "r") or die ("Erro ao abrir o arquivo!");
        $cont = 0;
        $linha = fgets($arquivo);
        while (!feof($arquivo)){
            $linha = fgets($arquivo);
            $colunaDados = explode(";",$linha);
            echo "<tr>";
            echo "<td>$colunaDados[0]</td>";
            echo "<td>$colunaDados[1]</td>";
            echo "<td>$colunaDados[2]</td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>