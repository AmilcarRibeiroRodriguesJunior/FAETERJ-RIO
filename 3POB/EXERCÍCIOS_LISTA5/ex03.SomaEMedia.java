import java.util.ArrayList;
import java.util.Scanner;

public class SomaEMedia{
    public static void main(String[] args){
        Scanner scanner = new Scanner(System.in);
        ArrayList<Double> numeros = new ArrayList<>();

        System.out.println("Digite numeros (digite -1 para parar):");

        double entrada=0;
        while(entrada!=-1){
            entrada=scanner.nextDouble();
            if(entrada!=-1){
                numeros.add(entrada);
            }
        }

        double soma=0;
        for(int i=0; i<numeros.size(); i++){
            soma += numeros.get(i);
        }

        if(numeros.size() > 0){
            double media = soma/numeros.size();
            System.out.println("\nSoma dos numeros: " + soma);
            System.out.println("\nMedia dos numeros: " + media);
        } else {
            System.out.println("\nNenhum numero valido foi inserido.");
        }
        scanner.close();
    }
}