<?php
if($_SERVER['REQUEST_METHOD']=="POST"){
    $matricula=$_POST["matricula"];
    $nome=$_POST["nome"];
    $email=$_POST["email"];
    $erros=[];

    if(!preg_match("/^[0-9]{1,10}", $matricula)){
        $erros[] = "Matrícula inválida! Use apenas números (máx. 10 dígitos).";
    }

    if(!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ\s]{3,50}$", $nome)){
        $erros[] = "Nome inválido! Use apenas letras e espaços.";
    }

    if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[A-Za-z]{2,}$", $email)) {
        $erros[] = "E-mail inválido!";
    }

    if(empty($erros)){
        $linha = $matricula . " | " . $nome . " | " . $email . PHP_EOL;
        file_put_contents("alunos.txt", $linha, FILE_APPEND);

        echo "<h2>Aluno cadastrado com sucesso!</h2>";
        echo "<p><strong>Matrícula:</strong> " . htmlspecialchars($matricula) . "</p>";
        echo "<p><strong>Nome:</strong> " . htmlspecialchars($nome) . "</p>";
        echo "<p><strong>E-mail:</strong> " . htmlspecialchars($email) . "</p>";
    } else {
        echo "<h2>Erros encontrados:</h2>";
        echo "<ul>";
        foreach($erros as $erro){
            echo "<li>" . htmlspecialchars($erro) . "</li>";
        }
        echo "<ul>";
        echo "<a href='incluirAluno.html'>Voltar</a>";
    }
}
?>