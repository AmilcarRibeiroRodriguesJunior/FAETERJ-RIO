package dominio;

public class CaixaEletronico {
    private double saldo;

    public CaixaEletronico(double saldoInicial){
        this.saldo=saldoInicial;
    }

    public void sacar(double valor){
        if(valor % 10 != 0){
            System.out.println("O valor do saque deve ser múltiplo de R$10!");
        } else if (valor>saldo){
            System.out.println("Saldo insuficiente para saque!");
        } else if (valor<=0){
            System.out.println("Valor inválido para saque!");
        } else {
            saldo -= valor;
            System.out.println("Saque de R$" + valor + " realizado com sucesso!");
            exibirSaldo();
        }
    }

    public void exibirSaldo(){
        System.out.printf("Saldo atual: R$%.2f%n", saldo);
    }
}
