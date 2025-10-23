package dominio;

public class Aluno{
    private String nome;
    private double nota;

    public Aluno(String nome, double nota){
        this.nome=nome;
        this.nota=nota;
    }

    public boolean validarNota(){
        return nota >= 0 && nota <= 10;
    }

    public String classificarAluno(){
        if(nota >= 7){
            return "Aprovado";
        } else if (nota >= 5){
            return "Recuperação";
        } else {
            return "Reprovado";
        }
    }

    public void exibirResultado(){
        System.out.println("\n--- Resultado do Aluno ---");
        System.out.println("Nome: " + nome);
        System.out.printf("Nota: %.2f%n", nota);
        System.out.println("Classificação: " + classificarAluno());
    }
}
