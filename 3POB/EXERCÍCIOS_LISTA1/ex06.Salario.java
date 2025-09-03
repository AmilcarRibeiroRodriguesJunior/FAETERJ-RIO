import java.util.Scanner;

public class Salario{
    public static void main(String[] args){
        Scanner sc=new Scanner(System.in);
        
        System.out.print("Digite seu salario bruto: ");
        double salarioBruto=sc.nextDouble();
        
        double desconto = 150.0;
        
        double salarioLiquido = salarioBruto - desconto;
        
        System.out.println("Salario liquido: " + salarioLiquido);
        
        sc.close();
    }
}