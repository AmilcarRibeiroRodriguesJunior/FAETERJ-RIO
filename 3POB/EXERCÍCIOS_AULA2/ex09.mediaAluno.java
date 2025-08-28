import java.util.Scanner;

public class MediaAluno {
    public static void main(String[] args){
        Scanner scanner = new Scanner(System.in);

        System.out.print("Digite a primeira nota: ");
        double nota1=scanner.nextDouble();

        System.out.print("Digite a segunda nota: ");
        double nota2=scanner.nextDouble();

        double media=(nota1+nota2 / 2);
        System.out.println("Media " + media);

        if(media>=7){
            System.out.println("Aprovado");
        } else {
            System.out.print("Digite a terceira nota (peso 2): ");
            double nota3=scanner.nextDouble();

            double novaMedia=(nota1 + nota2 + (nota3*2)) / 4;
            System.out.println("Nova media: " + novaMedia);

            if(novaMedia>=6){
                System.out.println("Aprovado");
            } else {
                System.out.println("Reprovado");
            }
        }
        scanner.close();
    }
}