#include <iostream>
using namespace std;

int main() {

    int escolha;
    
    
    do
    {
    
    cout << "Escolha o exercicio: " << endl;
    cout << "Cód | Exe" << endl;
    cout << "1  ---  1" << endl << "2  ---  2" << endl << "3  ---  3" << endl;
    cout << "4  ---  4" << endl << "5  ---  5" << endl << "6  ---  6" << endl;
    cout << "7  ---  7" << endl << "0  ---  Parar" << endl << "\n";
    cin >> escolha;

        
        switch(escolha)
        {
       
                case 1:
                        //Soma simples
                        int soma1, soma2, soma;
                    
                        cout << "Digite o primeiro numero: ";
                        cin >> soma1;
                    
                        cout << "Digite o segundo numero: ";
                        cin >> soma2;
                    
                        soma = soma1 + soma2;
                    
                        cout << "A soma de " << soma1 << " + " << soma2 << " = " << soma << endl << "\n";
                break;
            
                case 2:
                        //Multiplicação
                        int mult1, mult2, mult3, mult;
                    
                        cout << "Digite o primeiro numero: ";
                        cin >> mult1;
                    
                        cout << "Digite o segundo numero: ";
                        cin >> mult2;
                        
                        cout << "Digite o terceiro numero:";
                        cin >> mult3;
                    
                        mult = mult1 * mult2 * mult3;
                    
                        cout << "O produto de " << mult1 << " x " << mult2 << " x " << mult3 << " = " << mult << endl << "\n";
                break;
                
            
                case 3:
                        //Divisão
                        double div1, div2, divisão;
                    
                        cout << "Digite o primeiro numero: ";
                        cin >> div1;
                    
                        cout << "Digite o segundo numero: ";
                        cin >> div2;
                    
                        divisão = div1 / div2;
                    
                        cout << "A divisão de " << div1 << " / " << div2 << " = " << divisão << endl << "\n";
                break;
            
                case 4:
                        //Média ponderada
                        int nota1, nota2, m;
                    
                        cout << "Digite a primeira nota(peso 2): ";
                        cin >> nota1;
                    
                        cout << "Digite a segunda nota(peso 3): ";
                        cin >> nota2;
                    
                        m = (nota1 * 2 + nota2 * 3) / 5;
                    
                        cout << "A média ponderada é " << m <<  endl << "\n";
                break;
            
                case 5:
                        //Desconto de 10%
                        double produto;
                    
                        cout << "Digite a o preço do produto: ";
                        cin >> produto;
                        
                        produto = produto - (produto / 10);
                    
                        cout << "O preço com 10 porcento de desconto é " << produto <<  endl << "\n";
                break;
            
                case 6:
                        //Comissão de 4%
                        double salario, vendas, comissao;
                    
                        cout << "Digite o salário: ";
                        cin >> salario;
                        
                        cout << "Digite as vendas efetuadas: ";
                        cin >> vendas;
                        
                        comissao = (vendas * 4) / 100;
                        salario = salario + comissao;
                    
                    
                        cout << "O salário com comissão: " << salario <<  endl << "\n";
                break;
            
                case 7:
                        //+peso ou -peso
                        double peso, menor_peso, maior_peso;
                    
                        cout << "Digite o peso: ";
                        cin >> peso;
                        
                        maior_peso = peso + (peso * 15) / 100;
                        menor_peso = peso - (peso * 20 / 100); 
                    
                    
                        cout << "Se engordar: " << maior_peso <<  endl;
                        cout << "Se emagrecer: " << menor_peso <<  endl << "\n";
                break;
        }
    }
    while (escolha != 0);

    return 0;
}





