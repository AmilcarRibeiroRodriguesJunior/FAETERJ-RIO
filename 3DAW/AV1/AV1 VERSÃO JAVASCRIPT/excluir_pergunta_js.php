<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Pergunta de Múltipla Escolha</title>
    <script>
        function ExcluirPergunta() {
            let id = document.getElementById("id").value;
            
            let msgErro = ValidaForm(id);
            if (msgErro != "") {
                document.getElementById("msg").innerHTML = msgErro;
                return;
            }

            let xmlhttp = new XMLHttpRequest();
            console.log("Iniciando requisição de exclusão");
            
            xmlhttp.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    console.log("Resposta recebida: " + this.responseText);
                    document.getElementById("msg").innerHTML = this.responseText;
                    document.getElementById("id").value = "";
                } else if (this.readyState == 4){
                    console.log("Requisição falhou: " + this.status);
                    document.getElementById("msg").innerHTML = "Erro ao excluir pergunta.";
                } else {
                    console.log("Estado da requisição: " + this.readyState);
                }
            }
            
            xmlhttp.open("GET", "excluir_pergunta.php?id=" + encodeURIComponent(id));
            xmlhttp.send();
            console.log("Requisição enviada");
        }
        
        function ValidaForm(pid) {
            let msg = "";
            if (pid == "") {
                msg = "ID não preenchido. <br>";
            }
            return msg;
        }
    </script>
</head>
<body>
<h1>Excluir Pergunta de Múltipla Escolha</h1>
<br>
<a href="criar_pergunta_js.html">Criar Pergunta</a><br>
<a href="alterar_pergunta_js.php">Alterar Pergunta</a><br>
<a href="listar_perguntas_js.php">Listar Perguntas</a><br>
<br>
<form action="" method="GET" name="formExcluir" id="formExcluir">
    ID da Pergunta: <input type="text" name="id" id="id" required> <br>
    <br><br>
    <input type="button" value="Excluir" onclick="ExcluirPergunta();">
</form>
<p id="msg"></p>
<br>
</body>
</html>