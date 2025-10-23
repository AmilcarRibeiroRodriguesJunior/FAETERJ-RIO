package controle;

import dominio.CaixaEletronico;
import java.util.Scanner;

public class SimuladorCaixa{
    public static void main(String[] args){
        Scanner sc=new Scanner(System.in);

        System.out.print("Digite o saldo inicial da conta: ");
        double saldoInicial=sc.nextDouble();

        CaixaEletronico caixa=new CaixaEletronico(saldoInicial);

        int opcao=-1;

        while(opcao != 0){
            System.out.println("\n=== CAIXA ELETRÔNICO ===");
            System.out.println("1 - Sacar");
            System.out.println("2 - Exibir saldo");
            System.out.println("0 - Sair");
            System.out.print("Escolha uma opção: ");
            opcao = sc.nextInt();

            if(opcao==1){
                System.out.print("Digite o valor do saque: ");
                double valor=sc.nextDouble();
                caixa.sacar(valor);
            } else if(opcao == 2){
                caixa.exibirSaldo();
            } else if(opcao == 0){
                System.out.println("Encerrando o sistema. Até logo!");
            } else {
                System.out.println("Opção inválida! Tente novamente.");
            }
        }

        sc.close();
    }
}
