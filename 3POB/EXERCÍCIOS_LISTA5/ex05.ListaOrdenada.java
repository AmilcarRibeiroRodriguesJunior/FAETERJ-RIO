import java.util.ArrayList;
import java.util.Collections;
import java.util.Scanner;

public class ListaOrdenada{
    public static void main(String[] args){
        Scanner scanner = new Scanner(System.in);
        ArrayList<Integer> numeros = new ArrayList<>();

        String resposta = "s";
        while(resposta.equalsIgnoreCase("s")){
            System.out.print("Digite um numero inteiro: ");
            int num=scanner.nextInt();
            numeros.add(num);

            System.out.print("Deseja adicionar outro numero? [s/n]: ");
            resposta=scanner.next();
        }

        Collections.sort(numeros);

        System.out.println("\nLista ordenada em ordem crescente:");
        for(int i=0; i<numeros.size(); i++){
            System.out.println(numeros.get(i));
        }
        scanner.close();
    }
}