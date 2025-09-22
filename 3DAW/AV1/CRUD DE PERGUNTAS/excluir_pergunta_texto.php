<?php
if(!isset($_GET['id'])){ 
    echo "ID não especificado."; 
    exit;
}
$idExcluir=$_GET['id'];
$arquivo="perguntas_texto.txt";
$linhas=array();

$fp=fopen($arquivo, "r") or die("Erro ao abrir arquivo!");
$cabecalho = fgets($fp);
while(($linha = fgets($fp)) !== false){
    if(trim($linha) != "") 
        $linhas[]=$linha;
}
fclose($fp);

$novasLinhas=array();
for($i=0; $i<count($linhas); $i=$i+1){
    $partes=explode("|", trim($linhas[$i]));
    if($partes[0] != $idExcluir) 
        $novasLinhas[]=$linhas[$i];
}

$fp=fopen($arquivo, "w") or die("Erro ao abrir arquivo!");
fwrite($fp, $cabecalho);
for($i=0; $i<count($novasLinhas); $i=$i+1){ 
    fwrite($fp, $novasLinhas[$i]);
}
fclose($fp);

echo "<p>Pergunta de texto excluída com sucesso!</p>";
echo "<a href='listar_pergunta_texto.php'>Voltar à lista</a>";
?>
