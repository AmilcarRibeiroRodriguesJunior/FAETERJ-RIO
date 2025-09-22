<?php
$arquivo="perguntas_texto.txt";
$salvo=false;

$ultimoId=0;
if(file_exists($arquivo)){
    $fp=fopen($arquivo, "r");
    fgets($fp);
    while(($linha = fgets($fp)) !== false){
        if(trim($linha) == "") continue;
        $partes = explode("|", trim($linha));
        $ultimoId = intval($partes[0]);
    }
    fclose($fp);
}

if($_POST){
    $novoId=$ultimoId + 1;
    $pergunta=trim($_POST['pergunta']);
    $resposta=trim($_POST['resposta']);

    if($pergunta == "" || $resposta == ""){
        echo "<p>Preencha pergunta e resposta.</p>";
    } else {
        $arq = fopen($arquivo, "a") or die("Erro ao abrir arquivo!");
        if(filesize($arquivo) == 0){
            fwrite($arq, "id|pergunta|resposta".PHP_EOL);
        }
        fwrite($arq, $novoId."|".$pergunta."|".$resposta.PHP_EOL);
        fclose($arq);
        $salvo=true;
    }
}

if($salvo){
    echo "<h2>Pergunta de texto salva com sucesso!</h2>";
    echo "<a href='criar_pergunta_texto.php'>Criar outra pergunta</a> | <a href='listar_pergunta_texto.php'>Ver lista</a>";
    exit;
}
?>

<h2>Criar Pergunta de Resposta de Texto</h2>
<form method="post">
    Pergunta:<br>
    <input type="text" name="pergunta"><br><br>

    Resposta esperada:<br>
    <input type="text" name="resposta"><br><br>

    <button type="submit">Salvar Pergunta de Texto</button>
</form>
