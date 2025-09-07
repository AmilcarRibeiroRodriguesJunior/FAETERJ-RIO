import java.util.Scanner;

public class SomaNaturais{
    public static void main(String[] args){
        Scanner input = new Scanner(System.in);

        System.out.print("Digite um numero N: ");
        int N=input.nextInt();

        int soma=0;

        for(int i=0; i<=N; i++){
            soma += i;
        }

        System.out.println("A soma dos primeiros " + N + " numeros naturais eh: " + soma);

        input.close();
    }
}