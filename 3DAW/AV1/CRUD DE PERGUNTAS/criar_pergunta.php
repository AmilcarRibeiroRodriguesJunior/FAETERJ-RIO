<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Pergunta de Múltipla Escolha</title>
</head>
<body>
</html>

<?php
$arquivo="perguntas.txt";
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
    $respostas=array(
        trim($_POST['opA']),
        trim($_POST['opB']),
        trim($_POST['opC']),
        trim($_POST['opD']),
        trim($_POST['opE'])
    );
    $correta = trim($_POST['correta']);

    $valida = false;
    for($i=0; $i<count($respostas); $i=$i+1){
        if($respostas[$i] == $correta) 
            $valida = true;
    }

    if(!$valida){
        echo "<p>A resposta correta deve estar entre as alternativas preenchidas!</p>";
    } else {
        $arq = fopen($arquivo, "a") or die("Erro ao abrir arquivo!");
        if(filesize($arquivo) == 0){
            fwrite($arq, "id|pergunta|respostas|correta".PHP_EOL);
        }
        fwrite($arq, $novoId."|".$pergunta."|".implode(";", $respostas)."|".$correta.PHP_EOL);
        fclose($arq);
        $salvo = true;
    }
}

if($salvo){
    echo "<h2>Pergunta salva com sucesso!</h2>";
    echo "<a href='criar_pergunta.php'>Criar outra pergunta</a> | <a href='listar_perguntas.php'>Ver lista</a>";
    exit;
}
?>

<h2>Criar Pergunta de Múltipla Escolha</h2>
<form method="post">
    Pergunta:<br>
    <input type="text" name="pergunta"><br><br>

    Alternativa A:<br>
    <input type="text" name="opA"><br><br>
    Alternativa B:<br>
    <input type="text" name="opB"><br><br>
    Alternativa C:<br>
    <input type="text" name="opC"><br><br>
    Alternativa D:<br>
    <input type="text" name="opD"><br><br>
    Alternativa E:<br>
    <input type="text" name="opE"><br><br>

    Resposta correta:<br>
    <input type="text" name="correta"><br><br>

    <button type="submit">Salvar Pergunta</button>
</form>
