import java.util.Scanner;

public class MediaNotas{
    public static void main(String[] args){
        Scanner sc = new Scanner(System.in);
        
        double[] notas = new double[4];
        double soma=0;
        
        System.out.println("Digite as 4 notas: ");
        for(int i=0; i<notas.length; i++){
            System.out.println("Nota " + (i+1) + ": ");
            notas[i]=sc.nextDouble();
            soma += notas[i];
        }
        
        double media = soma / notas.length;
        
        System.out.printf("Media: %.2f\n", media);
        
        if(media>=7){
            System.out.println("Aprovado");
        } else {
            System.out.println("Reprovado");
        }
        sc.close();
    }
}