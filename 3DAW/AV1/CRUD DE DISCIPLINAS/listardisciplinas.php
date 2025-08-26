<?php
    $arquivo=fopen("disciplinas.txt", "r") or die("Erro ao abrir o arquivo!");
    $arquivo2=fopen("disciplinas2.txt", "w");
    echo "<h1>Lista de Disciplinas Cadastradas</h1>";

    while(!feof($arquivo)){
        $linha=fgets($arquivo);
            if (trim($linha)!= ""){
                $dados = explode(";", trim($linha));
                echo $dados[0] . " (" . $dados[1] . ") - " . $dados[2] . "h ";
                echo "<a href='excluirdisciplina.php?nome=" . urlencode($dados[0]) . "' onclick=\"return confirm('Tem certeza que deseja excluir esta disciplina?');\">Excluir</a><br>";
        }
    }
    fclose($arquivo);
    fclose($arquivo2);
?>