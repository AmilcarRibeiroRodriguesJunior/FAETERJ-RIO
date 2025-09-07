import java.util.Scanner;

public class NumerosPrimosIntervalo{
    public static void main(String[] args){
        Scanner input = new Scanner(System.in);

        System.out.print("Digite o numero inicial do intervalo: ");
        int inicio=input.nextInt();

        System.out.print("Digite o numero final do intervalo: ");
        int fim=input.nextInt();

        System.out.println("Numeros primos no intervalo de " + inicio + " ate " + fim + ": ");

        for(int num=inicio; num<=fim; num++){
            if(num>1){
                int contDivisores=0;

                for(int i=1; i<=num; i++){
                    if(num%i == 0){
                        contDivisores++;
                    }
                }
                
                if(contDivisores==2){
                    System.out.print(num + " ");
                }
            }
        }
        input.close();
    }
}