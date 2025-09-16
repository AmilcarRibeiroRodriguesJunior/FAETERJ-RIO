<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alunos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            font-family: 'Poppins', Arial, sans-serif;
            background: #f4f7f8;
            margin: 0;
            padding: 20px;
        }

        h1{
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table{
            width: 90%;
            max-width: 800px;
            margin: 0 auto;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 12px;
            overflow: hidden;
        }

        th, td{
            padding: 12px 15px;
            text-align: left;
        }

        th{
            background-color: #007BFF;
            color: #fff;
            font-weight: 600;
        }

        tr:nth-child(even){
            background-color: #f2f2f2;
        }

        tr:hover{
            background-color: #e6f0ff;
        }

        td{
            color: #555;
        }
    </style>
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

        fgets($arquivo);

        while (($linha = fgets($arquivo)) !== false){
            if(trim($linha) != ""){
                $colunaDados = explode(";", $linha);

                echo "<tr>";
                echo "<td>" . htmlspecialchars(trim($colunaDados[0])) . "</td>";
                echo "<td>" . htmlspecialchars(trim($colunaDados[1])) . "</td>";
                echo "<td>" . htmlspecialchars(trim($colunaDados[2])) . "</td>";
                echo "</tr>";
            }
        }
        fclose($arquivo);
        ?>
    </table>
</body>
</html>
