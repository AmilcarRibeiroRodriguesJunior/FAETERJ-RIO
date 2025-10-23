package controle;

import dominio.Aluno;
import java.util.Scanner;

public class GerenciadorAluno{
    public static void main(String[] args){
        Scanner sc=new Scanner(System.in);

        System.out.print("Digite o nome do aluno: ");
        String nome=sc.nextLine();

        double nota=-1;

        while(nota<0 || nota>10){
            System.out.print("Digite a nota do aluno (0 a 10): ");
            nota=sc.nextDouble();

            if(nota<0 || nota>10){
                System.out.println("Nota inválida! Digite novamente.");
            }
        }

        Aluno aluno=new Aluno(nome, nota);
        aluno.exibirResultado();

        sc.close();
    }
}
