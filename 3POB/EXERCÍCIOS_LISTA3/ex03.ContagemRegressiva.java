import java.util.Scanner;

public class ContagemRegressiva{
    public static void main(String[] args){
        Scanner input = new Scanner(System.in);

        System.out.print("Digite um numero inteiro: ");
        int numero=input.nextInt();

        if(numero<0){
            System.out.println("Por favor, digite um numero positivo!");
        } else {
            do{
                System.out.println(numero);
                numero--;
            } while(numero>=0);
        }
        input.close();
    }
}