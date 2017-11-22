<?php
namespace test\br\com\caelum\leilao\servico;

require_once 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use br\com\caelum\leilao\servico\GeradorDePagamento;
use DateTime;
use src\br\com\caelum\leilao\DAO\RepositorioDeLeiloes;
use src\br\com\caelum\leilao\DAO\RepositorioDePagamentos;
use src\br\com\caelum\leilao\dominio\Relogio;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\servico\Avaliador;
use src\br\com\caelum\leilao\servico\CriadorDeLeilao;

class GeradorDePagamentoTest extends TestCase 
{
    public function testDeveEmpurrarParaOProximoDiaUtilNoSabado() {
        
        $leiloes = $this->createMock(RepositorioDeLeiloes::class);
        $pagamentos = $this->createMock(RepositorioDePagamentos::class);
        $relogio = $this->createMock(Relogio::class);
        
        $sabado = new DateTime("2012-04-07");
        
        $relogio->method("hoje")->will(
            $this->returnValue($sabado)
        );
        
        $leilao = new CriadorDeLeilao();
        $leilao = $leilao->para("Playstation")
        ->lance(new Usuario("José da Silva"), 2000.0)
        ->lance(new Usuario("Maria Pereira"), 2500.0)
        ->constroi();
        
        $leiloes->method("encerrados")->will(
            $this->returnValue(array($leilao))
        );
        
        $gerador = new GeradorDePagamento($leiloes, $pagamentos, new Avaliador(),$relogio);
        $pagamentos = $gerador->gera();
        
        $pagamentoGerado = $pagamentos[0];
        
        $this->assertEquals(1, $pagamentoGerado->getData()->format("w"));
    }
    
    public function testDeveEmpurrarParaOProximoDiaUtilNoDomingo() {
        
        $leiloes = $this->createMock(RepositorioDeLeiloes::class);
        $pagamentos = $this->createMock(RepositorioDePagamentos::class);
        $relogio = $this->createMock(Relogio::class);
        
        $sabado = new DateTime("2012-04-08");
        
        $relogio->method("hoje")->will(
            $this->returnValue($sabado)
            );
        
        $leilao = new CriadorDeLeilao();
        $leilao = $leilao->para("Playstation")
        ->lance(new Usuario("José da Silva"), 2000.0)
        ->lance(new Usuario("Maria Pereira"), 2500.0)
        ->constroi();
        
        $leiloes->method("encerrados")->will(
            $this->returnValue(array($leilao))
            );
        
        $gerador = new GeradorDePagamento($leiloes, $pagamentos, new Avaliador(),$relogio);
        $pagamentos = $gerador->gera();
        
        $pagamentoGerado = $pagamentos[0];
        
        $this->assertEquals(1, $pagamentoGerado->getData()->format("w"));
    }
}

