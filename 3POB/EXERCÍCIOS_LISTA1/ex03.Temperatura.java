import java.util.Scanner;

public class Temperatura{
    public static void main(String[] args){
        Scanner sc=new Scanner(System.in);
        
        System.out.print("Digite a temperatura em graus Celsius: ");
        double celsius=sc.nextDouble();
        
        double fahrenheit = (celsius * 9/5) + 32;
        
        System.out.println("Temperatura convertida para Fahrenheit: " + fahrenheit);
        
        sc.close();
    }
}