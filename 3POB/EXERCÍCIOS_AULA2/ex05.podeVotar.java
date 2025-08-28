import java.util.Scanner;
import java.time.Year;

public class PodeVotar{
    public static void main(String[] args){
        Scanner scanner =new Scanner(System.in);

        System.out.print("Digite o ano de nascimento: ");
        int anoNascimento=scanner.nextInt();

        int anoAtual=Year.now().getValue();

        int idade=anoAtual - anoNascimento;

        System.out.println("Idade aproximada: " + idade + " anos.");

        if(idade>=16){
            System.out.println("Apto a ser eleitor");
        } else {
            System.out.println("Inapto a ser eleitor");
        }
        scanner.close();
    }
}