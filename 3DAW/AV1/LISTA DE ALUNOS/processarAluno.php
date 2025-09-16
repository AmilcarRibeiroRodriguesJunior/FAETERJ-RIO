<?php 
if($_SERVER['REQUEST_METHOD']=="POST"){
    $matricula=$_POST["matricula"];
    $nome=$_POST["nome"];
    $email=$_POST["email"];
    $erros=[];

    if(!preg_match("/^[0-9]{1,10}$/", $matricula)){
        $erros[] = "Matrícula inválida! Use apenas números (máx. 10 dígitos).";
    }

    if(!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ\s]{3,50}$/", $nome)){
        $erros[] = "Nome inválido! Use apenas letras e espaços.";
    }

    if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[A-Za-z]{2,}$/", $email)) {
        $erros[] = "E-mail inválido!";
    }

    echo '<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resultado Cadastro</title>
        <!-- Google Fonts: Poppins -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body{
                font-family: "Poppins", Arial, sans-serif;
                background: #f4f7f8;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
            }
            .container{
                background: #fff;
                padding: 30px 40px;
                border-radius: 12px;
                box-shadow: 0px 4px 10px rgba(0,0,0,0.15);
                width: 100%;
                max-width: 500px;
            }
            h2{
                text-align: center;
                color: #333;
            }
            p{
                font-size: 16px;
                color: #555;
                margin: 8px 0;
            }
            ul{
                color: #d9534f;
                font-weight: 500;
            }
            a{
                display: inline-block;
                margin-top: 15px;
                padding: 10px 20px;
                background: #007BFF;
                color: #fff;
                text-decoration: none;
                border-radius: 8px;
                transition: background 0.3s;
            }
            a:hover{
                background: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="container">';
    
    if(empty($erros)){
        echo "<h2>Aluno cadastrado com sucesso!</h2>";
        echo "<p><strong>Matrícula:</strong> " . htmlspecialchars($matricula) . "</p>";
        echo "<p><strong>Nome:</strong> " . htmlspecialchars($nome) . "</p>";
        echo "<p><strong>E-mail:</strong> " . htmlspecialchars($email) . "</p>";
        echo "<a href='incluirAluno.html'>Cadastrar outro aluno</a>";
    } else {
        echo "<h2>Erros encontrados:</h2>";
        echo "<ul>";
        foreach($erros as $erro){
            echo "<li>" . htmlspecialchars($erro) . "</li>";
        }
        echo "</ul>";
        echo "<a href='incluirAluno.html'>Voltar</a>";
    }
    echo '</div>
    </body>
    </html>';
}
?>