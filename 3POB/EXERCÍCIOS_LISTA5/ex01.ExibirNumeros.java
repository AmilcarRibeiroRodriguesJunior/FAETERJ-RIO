import java.util.ArrayList;
import java.util.Scanner;

public class ExibirNumeros{
    public static void main(String[] args){
        Scanner scanner = new Scanner(System.in);
        ArrayList<Integer> numeros = new ArrayList<>();

        String resposta = "s";

        while(resposta.equalsIgnoreCase("s")){
            System.out.print("Digite um numero inteiro: ");
            int numero=scanner.nextInt();
            numeros.add(numero);

            System.out.print("Deseja adicionar outro numero? [s/n]: ");
            resposta=scanner.next();
        }
        System.out.println("\nNumeros digitados: ");
        for(int i=0; i<numeros.size(); i++){
            System.out.println(numeros.get(i));
        }
        scanner.close();
    }
}