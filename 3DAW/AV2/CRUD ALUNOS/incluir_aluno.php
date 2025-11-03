<?php
$host="localhost";
$user="root";
$pass="";
$db="escola";

$conn=new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
    die("Erro na conexão: " . $conn->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome=$_POST['nome'];
    $matricula=$_POST['matricula'];
    $email=$_POST['email'];

    $sql="INSERT INTO alunos (nome, matricula, email) VALUES ('$nome', '$matricula', '$email')";

    if($conn->query($sql) === TRUE){
        echo "<script>
                alert('Aluno incluído com sucesso!');
                window.location.href='index.php';
              </script>";
        exit;
    } else {
        echo "<p>Erro ao incluir aluno: " . $conn->error . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Incluir Aluno</title>
</head>
<body>
    <h2>Adicionar Aluno</h2>
    <form action="incluir_aluno.php" method="POST">
        Nome: <input type="text" name="nome" required><br><br>
        Matrícula: <input type="text" name="matricula" required><br><br>
        Email: <input type="email" name="email" required><br><br>
        <input type="submit" value="Salvar">
    </form>
    <a href="index.php">Voltar</a>
</body>
</html>

