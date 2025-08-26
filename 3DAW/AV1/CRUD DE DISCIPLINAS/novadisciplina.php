<?php
$msg="";
if ($_SERVER['REQUEST_METHOD'] == 'POST')  {
    $nome=$_POST["nome"];
    $sigla=$_POST["sigla"];
    $carga=$_POST["carga"];
    
    if (!file_exists("disciplinas.txt")){
        $arqDisc = fopen("disciplinas.txt","w") or die("Erro ao criar arquivo!");
        $linha = "nome;sigla;carga\n";
        fwrite($arqDisc,$linha);
        fclose($arqDisc);
    }
    
    $arqDisc=fopen("disciplinas.txt","a") or die("Erro ao abrir arquivo!");
    
    $linha=$nome . ";" . $sigla . ";" . $carga . "\n";
    fwrite($arqDisc,$linha);
    fclose($arqDisc);
    
    $msg="Matéria incluída!";
}
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<h1>Incluir Nova Disciplina</h1>
<form action="novadisciplina.php" method="POST">
    Nome: <input type="text" name="nome">
    <br><br>
    Sigla: <input type="text" name="sigla">
    <br><br>
    Carga Horária: <input type="text" name="carga">
    <br><br>
    <input type="submit" value="Incluir Nova Disciplina">
</form>
<p><?php echo $msg ?></p>
<br>
</body>
</html>