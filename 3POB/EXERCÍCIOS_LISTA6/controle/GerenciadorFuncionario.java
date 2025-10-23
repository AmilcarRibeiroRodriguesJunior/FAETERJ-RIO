package controle;

import dominio.Funcionario;
import java.util.Scanner;

public class GerenciadorFuncionario{
    public static void main(String[] args){
        Scanner sc=new Scanner(System.in);

        System.out.print("Digite o nome do funcionário: ");
        String nome=sc.nextLine();

        System.out.print("Digite o salário base: ");
        double salarioBase=sc.nextDouble();
        sc.nextLine();

        System.out.print("Digite a categoria (A, B ou C): ");
        String categoria=sc.nextLine();

        Funcionario funcionario=new Funcionario(nome, salarioBase, categoria);
        funcionario.exibirDados();

        sc.close();
    }
}
