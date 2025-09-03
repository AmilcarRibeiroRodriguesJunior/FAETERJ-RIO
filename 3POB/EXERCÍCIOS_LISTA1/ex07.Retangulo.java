import java.util.Scanner;

public class Retangulo{
    public static void main(String[] args){
        Scanner sc=new Scanner(System.in);
        
        System.out.print("Digite a base do retangulo: ");
        double base=sc.nextDouble();
        
        System.out.print("Digite a altura do retangulo: ");
        double altura=sc.nextDouble();
        
        double area = base * altura;
        
        double perimetro = 2 * (base * altura);
        
        System.out.println("Area do retangulo: " + area);
        
        System.out.println("perimetro do retangulo: " + perimetro);
        
        sc.close();
    }
}
