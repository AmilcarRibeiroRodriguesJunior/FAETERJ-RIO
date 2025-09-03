import java.util.Scanner;

public class Volume{
    public static void main(String[] args){
        Scanner sc=new Scanner(System.in);
        
        double pi=3.14159;
        
        System.out.print("Digite o raio da esfera: ");
        double raio=sc.nextDouble();
        
        
        double volume = 4 * pi * (raio * raio * raio) / 3;
        
        System.out.println("Volume da esfera: " + volume);
        
        sc.close();
    }
}