import java.util.Scanner;

public class MaiorNumero{
    public static void main(String[] args){
        Scanner scanner= new Scanner(System.in);

        System.out.print("Digite o primeiro numero: ");
        int num1=scanner.nextInt();

        System.out.print("Digite o segundo numero: ");
        int num2=scanner.nextInt();

        if(num1 > num2){
            System.out.println("O maior numero eh: " + num1);
        } else if(num2>num1){
            System.out.println("O maior numero eh: " + num2);
        } else {
            System.out.println("Os dois numeros sao iguais!");
        }
        scanner.close();
    }
}