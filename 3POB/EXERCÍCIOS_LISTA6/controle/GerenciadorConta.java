package controle;

import dominio.ContaBancaria;
import java.util.Scanner;

public class GerenciadorConta{
    public static void main(String[] args){
        Scanner sc=new Scanner(System.in);
        System.out.print("Digite o nome do titular da conta: ");
        String titular = sc.nextLine();

        ContaBancaria conta = new ContaBancaria(titular);
        int opcao;

        do{
            System.out.println("\n=== MENU ===");
            System.out.println("1 - Depositar");
            System.out.println("2 - Sacar");
            System.out.println("3 - Consultar saldo");
            System.out.println("0 - Sair");
            System.out.print("Escolha uma opção: ");
            opcao = sc.nextInt();

            switch(opcao){
                case 1:
                    System.out.print("Digite o valor do depósito: ");
                    double deposito=sc.nextDouble();
                    conta.depositar(deposito);
                    break;
                case 2:
                    System.out.print("Digite o valor do saque: ");
                    double saque=sc.nextDouble();
                    conta.sacar(saque);
                    break;
                case 3:
                    conta.exibirSaldo();
                    break;
                case 0:
                    System.out.println("Encerrando o programa...");
                    break;
                default:
                    System.out.println("Opção inválida! Tente novamente.");
            }
        } while (opcao!=0);
        sc.close();
    }
}