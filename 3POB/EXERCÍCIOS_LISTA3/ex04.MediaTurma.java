import java.util.Scanner;

public class MediaTurma{
    public static void main(String[] args){
        Scanner input = new Scanner(System.in);

        System.out.println("Digite a quantidade de alunos na turma: ");
        int quantAlunos=input.nextInt();

        double somaNotas=0;

        for(int i=1; i<=quantAlunos; i++){
            System.out.print("Digite a nota do aluno " + i + ": ");
            double nota=input.nextDouble();
            somaNotas += nota;
        }
        double media = somaNotas / quantAlunos;

        System.out.println("Media da turma: " + media);

        input.close();
    }
}