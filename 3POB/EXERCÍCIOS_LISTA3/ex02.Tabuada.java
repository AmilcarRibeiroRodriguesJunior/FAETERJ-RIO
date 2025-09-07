import java.util.Scanner;

public class Tabuada{
    public static void main(String[] args){
        Scanner input = new Scanner(System.in);

        System.out.print("Digite um numero inteiro: ");
        int numero=input.nextInt();

        int cont=1;

        while(cont<=10){
            System.out.println(numero + " x " + cont + " = " + (numero * cont));
            cont++;
        }
        input.close();
    }
}