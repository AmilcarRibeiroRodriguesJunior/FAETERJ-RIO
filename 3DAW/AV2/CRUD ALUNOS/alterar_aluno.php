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
    $id=$_POST['id'];
    $nome=$_POST['nome'];
    $matricula=$_POST['matricula'];
    $email=$_POST['email'];

    $sql="UPDATE alunos SET nome='$nome', matricula='$matricula', email='$email' WHERE id=$id";

    if($conn->query($sql) === TRUE){
        echo "<script>
                alert('Aluno atualizado com sucesso!');
                window.location.href='index.php';
              </script>";
        exit;
    } else {
        echo "<p>Erro ao atualizar: " . $conn->error . "</p>";
    }
}

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $result=$conn->query("SELECT * FROM alunos WHERE id = $id");

    if($result->num_rows > 0){
        $aluno=$result->fetch_assoc();
    } else {
        echo "<p>Aluno não encontrado.</p>";
        exit;
    }
} else {
    echo "<p>ID não fornecido.</p>";
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Alterar Aluno</title>
</head>
<body>
    <h2>Alterar Aluno</h2>
    <form action="alterar_aluno.php" method="POST">
        <input type="hidden" name="id" value="<?= $aluno['id'] ?>">
        Nome: <input type="text" name="nome" value="<?= $aluno['nome'] ?>" required><br><br>
        Matrícula: <input type="text" name="matricula" value="<?= $aluno['matricula'] ?>" required><br><br>
        Email: <input type="email" name="email" value="<?= $aluno['email'] ?>" required><br><br>
        <input type="submit" value="Atualizar">
    </form>
    <a href="index.php">Voltar</a>
</body>
</html>
