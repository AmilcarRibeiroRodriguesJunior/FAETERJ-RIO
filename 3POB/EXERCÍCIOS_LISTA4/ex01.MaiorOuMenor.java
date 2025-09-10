import java.util.Scanner;

public class MaiorOuMenor{
    public static void main(String[] args){
    Scanner sc= new Scanner(System.in);
    
    int[] numeros = new int[5];
    
    System.out.println("Digite 5 numeros inteiros: ");
    for(int i=0; i<numeros.length; i++){
        System.out.print("Numero " + (i+1) + ": ");
        numeros[i]=sc.nextInt();
    }
    
    int maior=numeros[0], menor=numeros[0];
    
    for(int i=1; i<numeros.length; i++){
        if(numeros[i] > maior){
            maior=numeros[i];
        }
        if(numeros[i]<menor){
            menor=numeros[i];
        }
    }
    
    System.out.println("Maior numero: " + maior);
    System.out.println("Menor numero: " + menor);
    
    sc.close();
    }
}