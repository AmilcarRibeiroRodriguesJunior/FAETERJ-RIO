import java.util.Scanner;

public class Salario{
    public static void main(String[] args) {
        Scanner sc = new Scanner(System.in);

        System.out.print("Digite o salario bruto: ");
        double salarioBruto=sc.nextDouble();

        System.out.print("Digite o sexo (M para masculino, F para feminino): ");
        char sexo=sc.next().toUpperCase().charAt(0);

        double desconto, salarioLiquido;

        if(sexo=='M'){
            desconto = salarioBruto*0.05;
        } else {
            desconto = salarioBruto*0.03;
        }
        salarioLiquido = salarioBruto - desconto;

        System.out.printf("Desconto: %.2f \n", desconto);
        System.out.printf("Salario liquido: %.2f", salarioLiquido);

        sc.close();
    }
}