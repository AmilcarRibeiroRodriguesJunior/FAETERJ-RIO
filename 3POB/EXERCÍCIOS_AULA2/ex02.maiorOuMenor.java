import java.util.Scanner;

public class Exercicio2 {
    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);

        // Ler a idade do utilizador
        System.out.print("Digite a sua idade: ");
        int idade = scanner.nextInt();

        if(idade>=18){
            System.out.println("Maior de idade");
        } else {
            System.out.println("Menor de idade");
        }
        scanner.close();
    }
}