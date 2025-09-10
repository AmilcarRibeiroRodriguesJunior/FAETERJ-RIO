import java.util.Scanner;

public class InversaoArray{
    public static void main(String[] args){
        Scanner sc = new Scanner(System.in);
        
        int[] numeros = new int[6];
        
        System.out.println("Digite 6 numeros inteiros: ");
        for(int i=0; i<numeros.length; i++){
            System.out.println("Numero " + (i+1) + ": ");
            numeros[i] = sc.nextInt();
        }
        
        System.out.println("\nNumeros na ordem inversa: ");
        for(int i=numeros.length - 1; i>=0; i--){
            System.out.println(numeros[i]);
        }
        sc.close();
    }
}