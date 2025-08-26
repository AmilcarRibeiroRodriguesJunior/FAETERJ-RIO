<?php
    $materia=$_POST["materia"];
    $arquivo=fopen("disciplinas.txt", "r") or die ("Erro ao abrir o arquivo!");
    while(!feof($arquivo)){
        $linha=fgets($arquivo);
        $colunaDados=explode(";",$linha);
        if($colunaDados[0] == $materia){
            break;
        }
    }
    fclose($arquivo);
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <h1>Digite o que deseja editar: </h1>
    <form action="alterardisciplina3.php" method="POST">
        <input type="text" name="nome" value= "<?php echo $colunaDados[0]; ?>">
        <input type="text" name="sigla" value= "<?php echo $colunaDados[1]; ?>">
        <input type="text" name="carga" value= "<?php echo $colunaDados[2]; ?>">
        <input type="submit" value="Editar" href="alterardisciplina3.php">
    </form>
</body>
</html>