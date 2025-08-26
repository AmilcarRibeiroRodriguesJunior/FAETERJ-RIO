<?php
if (isset($_GET['nome'])){
    $nomeExcluir = $_GET['nome'];

    $arquivo=fopen("disciplinas.txt", "r");
    $arquivo2=fopen("disciplinas2.txt", "w");

    while(!feof($arquivo)){
        $linha=fgets($arquivo);
        if(trim($linha) != ""){
            $dados=explode(";", trim($linha));
            if($dados[0] != $nomeExcluir){
                fwrite($arquivo2, $linha);
            }
        }
    }

    fclose($arquivo);
    fclose($arquivo2);

    rename("disciplinas2.txt", "disciplinas.txt");
    echo "Disciplina excluída com sucesso!";
}
?>
<br><br>
<a href="listardisciplinas.php">Voltar para lista</a>