import java.util.Scanner;

public class VerfificaIdade{
    public static void main(String[] args){
        Scanner input = new Scanner(System.in);

        System.out.print("Digite a sua idade: ");
        int idade=input.nextInt();

        if(idade >= 18){
            System.out.println("Maior de idade");
        }
        input.close();
    }
}