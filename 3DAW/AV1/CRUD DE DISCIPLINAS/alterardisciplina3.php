<?php
    $arquivo=fopen("disciplinas.txt", "r");
    $arquivo2=fopen("disciplinas2.txt", "w");
    $nome=$_POST["nome"];
    $sigla=$_POST["sigla"];
    $carga=$_POST["carga"];

    while(!feof($arquivo)){
        $linha=fgets($arquivo);
        if(trim($linha) != ""){
            $dados=explode(";", trim($linha));

            if($dados[0] == $nome){
                $novaLinha=$nome . ";" . $sigla . ";" . $carga . "\n";
                fwrite($arquivo2, $novaLinha);
            } else {
                fwrite($arquivo2, $linha);
            }
        }
    }
    fclose($arquivo);
    fclose($arquivo2);

    rename("disciplinas2.txt", "disciplinas.txt");
    echo "Disciplina alterada com sucesso!";
?>