import java.util.Scanner;

public class Soma{
    public static void main(String[] args){
        Scanner input=new Scanner(System.in);

        System.out.print("Digite o primeiro numero: ");
        double num1=input.nextDouble();

        System.out.print("Digite o segundo numero: ");
        double num2=input.nextDouble();

        double soma=num1 + num2;
        System.out.println("Soma dos numeros: " + soma);

        input.close();
    }
}