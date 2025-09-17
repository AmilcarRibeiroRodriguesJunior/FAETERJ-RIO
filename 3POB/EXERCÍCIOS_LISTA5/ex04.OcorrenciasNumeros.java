import java.util.ArrayList;
import java.util.Scanner;

public class OcorrenciasNumero{
    public static void main(String[] args){
        Scanner scanner = new Scanner(System.in);
        ArrayList<Integer> numeros = new ArrayList<>();

        for(int i=0; i<10; i++){
            System.out.println("Digite o " +(i+1) + "° numero inteiro: ");
            int num=scanner.nextInt();
            numeros.add(num);
        }

        System.out.print("\nDigite um numero para verificar quantas vezes aparece: ");
        int procurar=scanner.nextInt();

        int cont=0;
        for(int i=0; i<numeros.size(); i++){
            if(numeros.get(i)==procurar){
                cont++;
            }
        }

        System.out.println("\nO numero " + procurar + " aparece " + cont + " vez(es) na lista.");

        scanner.close();
    }
}