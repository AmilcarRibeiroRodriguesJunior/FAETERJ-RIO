import java.util.ArrayList;
import java.util.Scanner;

public class RemoverElemento{
    public static void main(String[] args){
        Scanner scanner = new Scanner(System.in);
        ArrayList<String> nomes = new ArrayList<>();

        for(int i=0; i<5; i++){
            System.out.print("Digite o " + (i+1) + "° nome: ");
            String nome=scanner.nextLine();
            nomes.add(nome);
        }
        System.out.print("\nDigite um nome para remover: ");
        String remover=scanner.nextLine();

        if(nomes.remove(remover)){
            System.out.println("\nNome removido com sucesso!");
        } else {
            System.out.println("\nO nome \"" + remover + "\" não foi encontrado na lista.");
        }

        System.out.println("\nLista atualizada: ");
        for(int i=0; i<nomes.size(); i++){
            System.out.println(nomes.get(i));
        }
        scanner.close();
    }
}