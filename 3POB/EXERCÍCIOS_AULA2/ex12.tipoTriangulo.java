import java.util.Scanner;

public class TipoTriangulo{
    public static void main(String[] args){
        Scanner sc = new Scanner(System.in);

        System.out.print("Digite o valor de A: ");
        int A=sc.nextInt();

        System.out.print("Digite o valor de B: ");
        int B=sc.nextInt();

        System.out.print("Digite o valor de C: ");
        int C=sc.nextInt();

        if (A>=B + C || B>=A + C || C>=A + B){
            System.out.println("Não forma triângulo");
        } else {
            if(A==B && B==C){
                System.out.println("Equilatero");
            } else if (A==B || A==C || B==C){
                System.out.println("Isosceles");
            } else {
                System.out.println("Escaleno");
            }
        }
        sc.close();
    }
}