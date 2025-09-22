<?php
if(!isset($_GET['id'])){ 
    echo "ID não especificado."; 
    exit; 
}
$idEditar=$_GET['id'];
$arquivo="perguntas_texto.txt";
$linhas=array();

$fp=fopen($arquivo, "r") or die("Erro ao abrir arquivo!");
$cabecalho=fgets($fp);
while(($linha = fgets($fp)) !== false){
    if(trim($linha) != "") $linhas[] = $linha;
}
fclose($fp);

$indice=-1;
for($i=0; $i<count($linhas); $i=$i+1){
    $partes = explode("|", trim($linhas[$i]));
    if($partes[0] == $idEditar){ 
        $indice=$i; 
        break;
    }
}

if($indice == -1){ 
    echo "Pergunta não encontrada."; 
    exit; 
}

$dados=explode("|", trim($linhas[$indice]));
$perguntaAtual=$dados[1];
$respostaAtual=$dados[2];

if($_POST){
    $pergunta=trim($_POST['pergunta']);
    $resposta=trim($_POST['resposta']);

    if($pergunta == "" || $resposta == ""){
        echo "<p>Preencha pergunta e resposta.</p>";
    } else {
        $linhas[$indice]=$idEditar."|".$pergunta."|".$resposta.PHP_EOL;
        $fp=fopen($arquivo, "w") or die("Erro ao abrir arquivo!");
        fwrite($fp, $cabecalho);
        for($i=0; $i<count($linhas); $i=$i+1) 
            fwrite($fp, $linhas[$i]);
        fclose($fp);

        echo "<p>Pergunta de texto alterada com sucesso!</p>";
        echo "<a href='listar_pergunta_texto.php'>Voltar à lista</a>";
        exit;
    }
}
?>

<h2>Alterar Pergunta de Texto</h2>
<form method="post">
    Pergunta:<br>
    <input type="text" name="pergunta" value="<?php echo $perguntaAtual; ?>"><br><br>

    Resposta esperada:<br>
    <input type="text" name="resposta" value="<?php echo $respostaAtual; ?>"><br><br>

    <button type="submit">Salvar Alterações</button>
</form>
