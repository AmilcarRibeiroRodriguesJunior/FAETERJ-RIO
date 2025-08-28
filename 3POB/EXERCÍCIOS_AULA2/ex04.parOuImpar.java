import java.util.Scanner;

public class ParOuImpar{
    public static void main(String[] args){
        Scanner scanner= new Scanner(System.in);

        System.out.print("Digite numero inteiro: ");
        int numero=scanner.nextInt();

        if(numero%2 == 0){
            System.out.println("PAR");
        } else {
            System.out.println("IMPAR");
        }
        scanner.close();
    }
}