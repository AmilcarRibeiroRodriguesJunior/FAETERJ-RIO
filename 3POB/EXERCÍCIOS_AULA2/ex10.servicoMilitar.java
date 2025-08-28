import java.util.Scanner;
import java.time.LocalDate;

public class ServicoMilitar{
    public static void main(String[] args){
        Scanner input = new Scanner(System.in);

        System.out.print("Digite o ano de nascimento: ");
        int anoNascimento=input.nextInt();

        System.out.print("Digite o sexo(M/F): ");
        char sexo=input.next().toUpperCase().charAt(0);

        int anoAtual=LocalDate.now().getYear();
        int idade=anoAtual-anoNascimento;

        if(sexo=='M' && idade==18){
            System.out.println("Servico Militar Obrigatorio");
        } else {
            System.out.println("Isento do Servico Militar");
        }
        input.close();
    }
}