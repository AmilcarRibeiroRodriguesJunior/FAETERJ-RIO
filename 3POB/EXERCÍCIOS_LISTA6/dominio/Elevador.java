package dominio;

public class Elevador{
    private int andarAtual;
    private int totalAndares;

    public Elevador(int totalAndares){
        this.totalAndares=totalAndares;
        this.andarAtual=0; 
    }

    public void subir(){
        if(andarAtual<totalAndares){
            andarAtual++;
            System.out.println("Subindo... Agora no andar " + andarAtual);
        } else {
            System.out.println("Você já está no último andar!");
        }
    }

    public void descer(){
        if(andarAtual>0){
            andarAtual--;
            System.out.println("Descendo... Agora no andar " + andarAtual);
        } else {
            System.out.println("Você já está no térreo!");
        }
    }

    public void exibirAndar(){
        System.out.println("Andar atual: " + andarAtual);
    }
}
