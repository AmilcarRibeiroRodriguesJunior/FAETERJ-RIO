<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Alterar Pergunta</title>
    <script>
        function AlterarPergunta(){
            let id = document.getElementById("id_pergunta").value;
            let pergunta = document.getElementById("nova_pergunta").value;

            let msgValidacao = ValidaForm(id, pergunta);
            if(msgValidacao !== ""){
                document.getElementById("msg").innerHTML=msgValidacao;
                return;
            }

            let xmlhttp = new XMLHttpRequest();
            console.log("Iniciando requisição...");

            xmlhttp.onreadystatechange=function(){
                if(this.readyState == 4 && this.status == 200){
                    console.log("Resposta recebida: " + this.responseText);
                    document.getElementById("msg").innerHTML = this.responseText;
                } else if (this.readyState < 4){
                    console.log("Carregando... estado " + this.readyState);
                } else if (this.readyState == 4 && this.status != 200){
                    console.log("Erro na requisição: " + this.status);
                    document.getElementById("msg").innerHTML="Erro ao alterar pergunta (" + this.status + ")";
                }
            };

            console.log("Abrindo conexão com o servidor...");
            xmlhttp.open("GET", "http://localhost/3daw/alterar_pergunta_js.php?id_pergunta=" + id + "&nova_pergunta=" + encodeURIComponent(pergunta));
            xmlhttp.send();
            console.log("Requisição enviada!");
        }

        function ValidaForm(pid, ppergunta){
            let msg="";
            if(pid === "") msg += "ID da pergunta não preenchido.<br>";
            if(ppergunta === "") msg += "Nova pergunta não preenchida.<br>";
            return msg;
        }
    </script>
</head>
<body>
    <h1>Alterar Pergunta</h1>
    <br>
    <a href
