import java.util.Scanner;

public class Triangulo{
    public static void main(String[] args){
        Scanner sc = new Scanner(System.in);

        System.out.print("Digite o valor de A: ");
        int A=sc.nextInt();

        System.out.print("Digite o valor de B: ");
        int B=sc.nextInt();

        System.out.print("Digite o valor de C: ");
        int C=sc.nextInt();

        if(A>=B+C || B>=A+C || C>=A+B){
            System.out.println("Nao forma triangulo");
        } else {
            System.out.println("Forma triangulo");
        }
        sc.close();
    }
}