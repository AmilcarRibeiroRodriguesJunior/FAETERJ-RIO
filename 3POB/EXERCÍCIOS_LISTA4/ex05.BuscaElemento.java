import java.util.Scanner;

public class BuscaElemento{
    public static void main(String[] args){
        Scanner sc = new Scanner(System.in);
        
        int[] numeros = new int[10];
        
        System.out.println("Digite 10 numeros inteiros: ");
        for(int i=0; i<numeros.length; i++){
            System.out.print("Numero: " + (i+1) + ": ");
            numeros[i]=sc.nextInt();
        }
        
        System.out.print("\nDigite o numero que deseja buscar: ");
        int busca=sc.nextInt();
        int posicao=-1;
        
        for(int i=0; i<numeros.length; i++){
            if(numeros[i]==busca){
                posicao=i;
                break;
            }
        }
        
        if(posicao!=-1){
            System.out.println("Numero encontrado na posicao: " + posicao);
        } else {
            System.out.println("Numero nao encontrado.");
        }
        sc.close();
    }
}