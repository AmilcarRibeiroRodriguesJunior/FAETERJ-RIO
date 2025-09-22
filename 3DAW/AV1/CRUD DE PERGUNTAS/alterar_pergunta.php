<?php
if(!isset($_GET['id'])){ 
    echo "ID não especificado."; 
    exit; 
}
$idEditar=$_GET['id'];
$arquivo="perguntas.txt";
$linhas=array();

$fp=fopen($arquivo, "r") or die("Erro ao abrir arquivo!");
$cabecalho=fgets($fp);
while(($linha = fgets($fp)) !== false){
    if (trim($linha) != "") 
        $linhas[] = $linha;
}
fclose($fp);

$indice=-1;
for($i=0; $i<count($linhas); $i=$i+1){
    $partes = explode("|", trim($linhas[$i]));
    if($partes[0] == $idEditar){
         $indice = $i; 
         break; 
    }
}

if($indice == -1) { echo "Pergunta não encontrada."; 
    exit; 
}

$dados=explode("|", trim($linhas[$indice]));
$perguntaAtual=$dados[1];
$respostasAtual=explode(";", $dados[2]);
$corretaAtual=$dados[3];

if($_POST){
    $pergunta=trim($_POST['pergunta']);
    $respostas=array(
        trim($_POST['opA']),
        trim($_POST['opB']),
        trim($_POST['opC']),
        trim($_POST['opD']),
        trim($_POST['opE'])
    );
    $correta=trim($_POST['correta']);

    $valida=false;
    for($i=0; $i<count($respostas); $i=$i+1){
        if($respostas[$i] == $correta) 
            $valida = true;
    }

    if(!$valida){ 
        echo "<p>A resposta correta deve estar entre as alternativas!</p>";
    } else {
        $linhas[$indice]=$idEditar."|".$pergunta."|".implode(";", $respostas)."|".$correta.PHP_EOL;
        $fp=fopen($arquivo, "w") or die("Erro ao abrir arquivo!");
        fwrite($fp, $cabecalho);
        for($i=0; $i<count($linhas); $i=$i+1) 
            fwrite($fp, $linhas[$i]);
        fclose($fp);

        echo "<p>Pergunta alterada com sucesso!</p>";
        echo "<a href='listar_perguntas.php'>Voltar à lista</a>";
        exit;
    }
}
?>

<h2>Alterar Pergunta</h2>
<form method="post">
    Pergunta:<br>
    <input type="text" name="pergunta" value="<?php echo $perguntaAtual; ?>"><br><br>

    Alternativa A:<br>
    <input type="text" name="opA" value="<?php echo $respostasAtual[0]; ?>"><br><br>
    Alternativa B:<br>
    <input type="text" name="opB" value="<?php echo $respostasAtual[1]; ?>"><br><br>
    Alternativa C:<br>
    <input type="text" name="opC" value="<?php echo $respostasAtual[2]; ?>"><br><br>
    Alternativa D:<br>
    <input type="text" name="opD" value="<?php echo $respostasAtual[3]; ?>"><br><br>
    Alternativa E:<br>
    <input type="text" name="opE" value="<?php echo $respostasAtual[4]; ?>"><br><br>

    Resposta correta:<br>
    <input type="text" name="correta" value="<?php echo $corretaAtual; ?>"><br><br>

    <button type="submit">Salvar Alterações</button>
</form>
