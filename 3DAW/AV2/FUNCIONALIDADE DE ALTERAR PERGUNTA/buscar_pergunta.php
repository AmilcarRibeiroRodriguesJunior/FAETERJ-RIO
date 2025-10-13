<?php
$codigo = "";
if(isset($_POST["codigo"])){
    $codigo=trim($_POST["codigo"]);
}

if($codigo == ""){
    echo json_encode(array("sucesso" => false, "erro" => "Código não informado"));
    exit;
}

$ficheiro="perguntas_alteradas.txt";

if(!file_exists($ficheiro)){
    echo json_encode(array("sucesso" => false, "erro" => "Ficheiro não encontrado"));
    exit;
}

$linhas=file($ficheiro, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$encontrada=false;
$texto="";

for($i=0; $i < count($linhas); $i++){
    $partes=explode(";", $linhas[$i]);
    if(count($partes) >= 2){
        $codigoLinha=trim($partes[0]);
        if($codigoLinha == $codigo){
            $texto=trim($partes[1]);
            $encontrada=true;
            break;
        }
    }
}

if($encontrada){
    echo json_encode(array("sucesso" => true, "pergunta" => $texto));
} else {
    echo json_encode(array("sucesso" => false));
}
?>
