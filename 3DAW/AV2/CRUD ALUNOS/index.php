<?php
$host="localhost";
$user="root";
$pass="";
$db="escola";

$conn=new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
    die("Erro na conexão: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Lista de Alunos</title>
    <script>
        function excluirAluno(id) {
            if(!confirm("Tem a certeza que deseja excluir este aluno?"))
                return;

            const formData=new FormData();
            formData.append("id", id);

            fetch("excluir_aluno.php",
            {
                method: "POST", body: formData
            }) .then(response => response.text()) .then(result => {
                console.log("Resposta do servidor:", result);
                if(result.trim() === "OK"){
                    alert("Aluno excluído com sucesso!");
                    location.reload();
                } else {
                    alert("Erro ao excluir: " + result);
                }
            })
            .catch(error => alert("Erro de rede: " + error));
        }
    </script>
</head>
<body>
    <h2>Alunos Cadastrados</h2>
    <a href="incluir_aluno.php">Novo Aluno</a>
    <br><br>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Matrícula</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
        <?php
        $result=$conn->query("SELECT * FROM alunos");
        while($row = $result->fetch_assoc()){
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nome']}</td>
                <td>{$row['matricula']}</td>
                <td>{$row['email']}</td>
                <td>
                    <a href='alterar_aluno.php?id={$row['id']}'>Editar</a> |
                    <a href='#' onclick='excluirAluno({$row['id']})'>Excluir</a>
                </td>
            </tr>";
        }
        ?>
    </table>
</body>
</html>
