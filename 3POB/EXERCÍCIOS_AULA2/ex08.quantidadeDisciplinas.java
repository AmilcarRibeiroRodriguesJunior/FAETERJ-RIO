import java.util.Scanner;

public class QuantidadeDisciplinas {
    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);

        System.out.print("Digite a quantidade de disciplinas em que o aluno nao alcancou a media: ");
        int quantidade = scanner.nextInt();

        if(quantidade==0){
            System.out.println("Aprovado");
        } else if(quantidade<=5){
            System.out.println("Recuperacao");
        } else {
            System.out.println("Reprovado");
        }
        scanner.close();
    }
}