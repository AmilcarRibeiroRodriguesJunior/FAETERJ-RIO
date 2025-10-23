package dominio;

public class Funcionario{
    private String nome;
    private double salarioBase;
    private String categoria;

    public Funcionario(String nome, double salarioBase, String categoria){
        this.nome=nome;
        this.salarioBase=salarioBase;
        this.categoria=categoria.toUpperCase();
    }

    public double calcularSalarioFinal(){
        double salarioFinal=salarioBase;

        switch(categoria){
            case "A":
                salarioFinal += salarioBase * 0.20;
                break;
            case "B":
                salarioFinal += salarioBase * 0.10;
                break;
            case "C":
                break;
            default:
                System.out.println("Categoria inválida! Nenhum bônus aplicado.");
        }

        return salarioFinal;
    }

    public void exibirDados(){
        System.out.println("\n--- Dados do Funcionário ---");
        System.out.println("Nome: " + nome);
        System.out.println("Categoria: " + categoria);
        System.out.printf("Salário base: R$%.2f%n", salarioBase);
        System.out.printf("Salário final: R$%.2f%n", calcularSalarioFinal());
    }
}
