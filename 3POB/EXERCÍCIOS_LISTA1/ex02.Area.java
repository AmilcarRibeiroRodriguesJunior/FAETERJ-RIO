import java.util.Scanner;

public class Area{
    public static void main(String[] args){
        Scanner sc=new Scanner(System.in);
        
        double pi=3.14159;
        
        System.out.print("Digite o raio do circulo: ");
        double raio=sc.nextDouble();
        
        double area = pi * (raio * raio);
        
        System.out.println("Area do circulo: " + area);
        
        sc.close();
    }
}