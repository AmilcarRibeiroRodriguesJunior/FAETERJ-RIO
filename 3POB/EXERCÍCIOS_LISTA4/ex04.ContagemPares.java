import java.util.Scanner;

public class ContagemPares{
    public static void main(String[] args){
        Scanner sc = new Scanner(System.in);
        
        int[] numeros = new int[8];
        int contPares=0;
        
        System.out.println("Digite 8 numeros inteiros: ");
        for(int i=0; i<numeros.length; i++){
            System.out.println("Numero: " + (i+1) + ": ");
            numeros[i]=sc.nextInt();
            
            if(numeros[i] % 2 == 0){
                contPares++;
            }
        }
        
        System.out.println("\nQuantidade de numeros pares: " + contPares);
        
        sc.close();
    }
}