<?php
    $v1=$_GET["a"];
    $v2=$_GET["b"];
    $opper=$_GET["op"];
    $resultado=0;

    switch($opper){
        case "Soma":
            $resultado=$v1 + $v2;
            break;
        case "Subtracao":
            $resultado=$v1 - $v2;
            break;
        case "Multiplicacao":
            $resultado=$v1 * $v2;
            break;
        case "Divisao":
            if($v2!=0){
                $resultado=$v1 / $v2;
            } else {
                $resultado="Erro!";
            }
            break;
    }
?>

<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <h1>CALCULADORA</h1>
    <form action="calculadora.php" method="GET" name="calculadora">
        Insira o primeiro numero: <input type='text' name="a">
        <br>
        Insira o segundo numero: <input type='text' name="b">
        <br><br>
        Escolha a operacao:
        <br>
        <input type="radio" name="op" value="Soma">
        <label for="Soma">Soma</label>

        <input type="radio" name="op" value="Subtracao">
        <label for="Subtracao">Subtracao</label>

        <input type="radio" name="op" value="Multiplicacao">
        <label for="Multiplicacao">Multiplicacao</label>

        <input type="radio" name="op" value="Divisao">
        <label for="Divisao">Divisao</label>
        <br><br>
        <input type='submit' name="Calcular">
    </form>
</body>
</html>

<?php
    echo "<h1>Resultado = $resultado</h1>";
    echo "<button onclick=\"window.location.href='calculadora.html'\">Voltar</button>";
?>