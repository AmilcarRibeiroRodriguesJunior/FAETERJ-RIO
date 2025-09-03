import java.util.Scanner;

public class Idade{
    public static void main(String[] args){
        Scanner sc=new Scanner(System.in);
        
        System.out.print("Digite a sua idade: ");
        int idade=sc.nextInt();
        
        int dias = idade * 365;
        
        System.out.println("Dias que voce ja viveu: " + dias);
        
        sc.close();
    }
}