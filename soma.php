<?php
    $v1 = $_GET["A"];
    $v2 = $_GET["B"];
    $op = $_GET["op"];
    $resultado = $v1 + $v2;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="soma.php" method="GET" name="Soma">
        <label for="fname"><A></label>
        a: <input type="text" name="A">
        <br>
        <label for="fname"><B></label>
        b: <input type="text" name="B">
        <br>
        <input type="submit" value="Enviar">
        <?php echo "<h1>Resultado: $resultado</h1>"; ?>
</body>
</html>
<?php
    echo "<br/><br/>";
?>