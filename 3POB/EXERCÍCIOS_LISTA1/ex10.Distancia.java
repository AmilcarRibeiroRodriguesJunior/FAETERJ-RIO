import java.util.Scanner;

public class Distancia{
    public static void main(String[] args) {
        Scanner input = new Scanner(System.in);
        
        System.out.print("Digite o valor de x1 e x2: ");
        double x1=input.nextDouble(), x2=input.nextDouble();
        
        System.out.print("Digite o valor de y1 e y2: ");
        double y1=input.nextDouble(), y2=input.nextDouble();
        
        double distancia = Math.sqrt(Math.pow(x2 - x1, 2) + Math.pow(y2 - y1, 2));
        
        System.out.println("Distancia entre eles no plano cartesiano: " + distancia);

        input.close();
    }
}