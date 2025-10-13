<?php
$codigo="";
$novaPergunta="";

if(isset($_POST["codigo"])){
    $codigo = trim($_POST["codigo"]);
}

if(isset($_POST["pergunta"])) {
    $novaPergunta = trim($_POST["pergunta"]);
}

if($codigo == "" || $novaPergunta == ""){
    echo json_encode(array("sucesso" => false, "erro" => "Dados incompletos"));
    exit;
}

$ficheiro="perguntas_alteradas.txt";

if (!file_exists($ficheiro)) {
    $handle = fopen($ficheiro, "w");
    fclose($handle);
}

$linhas=file($ficheiro, FILE_IGNORE_NEW_LINES);
$atualizado=false;

for($i=0; $i < count($linhas); $i++){
    $partes=explode(";", $linhas[$i]);
    if(count($partes) >= 2){
        $codigoLinha=trim($partes[0]);
        if($codigoLinha == $codigo){
            $linhas[$i]=$codigo . ";" . $novaPergunta;
            $atualizado=true;
            break;
        }
    }
}

if($atualizado == false){
    $linhas[]=$codigo . ";" . $novaPergunta;
}

file_put_contents($ficheiro, implode("\n", $linhas));

echo json_encode(array("sucesso" => true));
?>
