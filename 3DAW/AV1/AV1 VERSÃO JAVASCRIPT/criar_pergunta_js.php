<?php
$arquivo="perguntas.txt";
$salvo=false;

$ultimoId=0;
if(file_exists($arquivo)){
    $fp=fopen($arquivo, "r");
    fgets($fp);
    while(($linha=fgets($fp)) !== false){
        if(trim($linha) == "") continue;
        $partes=explode("|", trim($linha));
        $ultimoId=intval($partes[0]);
    }
    fclose($fp);
}

if($_GET){
    $novoId=$ultimoId+1;
    $pergunta=trim($_GET['pergunta'] ?? '');
    $respostas=array(
        trim($_GET['opA'] ?? ''),
        trim($_GET['opB'] ?? ''),
        trim($_GET['opC'] ?? ''),
        trim($_GET['opD'] ?? ''),
        trim($_GET['opE'] ?? '')
    );
    $correta=trim($_GET['correta'] ?? '');

    $valida=in_array($correta, $respostas);

    if($pergunta == "" || $correta == ""){
        echo "<p>Preencha todos os campos obrigatórios!</p>";
    } elseif (!$valida){
        echo "<p>A resposta correta deve estar entre as alternativas preenchidas!</p>";
    } else {
        $arq=fopen($arquivo, "a") or die("Erro ao abrir arquivo!");
        if(filesize($arquivo)==0){
            fwrite($arq, "id|pergunta|respostas|correta" . PHP_EOL);
        }
        fwrite($arq, $novoId . "|" . $pergunta . "|" . implode(";", $respostas) . "|" . $correta . PHP_EOL);
        fclose($arq);
        echo "<p>Pergunta salva com sucesso!</p>";
        echo "<a href='criar_pergunta_js.html'>Criar outra</a> | <a href='listar_perguntas.php'>Ver lista</a>";
    }
}
?>
