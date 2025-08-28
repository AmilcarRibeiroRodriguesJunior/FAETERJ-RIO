import java.util.Scanner;

public class PositivoOuNegativo {
    public static void main(String[] args) {
        Scanner sc = new Scanner(System.in);

        System.out.print("Digite um numero inteiro: ");
        int numero = sc.nextInt();

        if(numero>0){
            System.out.println("Positivo");
        } else if(numero<0){
            System.out.println("Negativo");
        } else {
            System.out.println("Nulo");
        }
        sc.close();
    }
}