import java.util.Scanner;

public class Conversao{
    public static void main(String[] args) {
        Scanner input = new Scanner(System.in);

        System.out.print("Digite o valor em reais (R$): ");
        double reais = input.nextDouble();

        System.out.print("Digite a cotação do dólar: ");
        double cotacaoDolar = input.nextDouble();

        double dolares = reais / cotacaoDolar;

        System.out.println("Valor equivalente em dólares (US$): " + dolares);

        input.close();
    }
}
