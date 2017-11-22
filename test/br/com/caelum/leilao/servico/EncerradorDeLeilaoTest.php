<?php
namespace test\br\com\caelum\leilao\servico;

require_once 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use DateInterval;
use DateTime;
use RuntimeException;
use src\br\com\caelum\leilao\DAO\RepositorioDeLeiloes;
use src\br\com\caelum\leilao\servico\CriadorDeLeilao;
use src\br\com\caelum\leilao\servico\EncerradorDeLeilao;
use src\br\com\caelum\leilao\servico\EnviadorDeEmail;

class EncerradorDeLeilaoTest extends TestCase {
    
    public function testDeveEncerrarLeiloesQueComecaramUmaSemanaAtras() {
        
        $antiga = new DateTime('1999-01-20');
        
        $leilao1 = new CriadorDeLeilao();
        $leilao1 = $leilao1->para("TV de plasma")
            ->naData($antiga)->constroi();
        
        $leilao2 = new CriadorDeLeilao();
        $leilao2 = $leilao2->para("Geladeira")
            ->naData($antiga)->constroi();
        
        $leiloesAntigos = array($leilao1,$leilao2);
        
        $daoFalso = $this->createMock(RepositorioDeLeiloes::class);
        $carteiroFalso = $this->createMock(EnviadorDeEmail::class);
        
        $daoFalso->method('correntes')->will(
            $this->returnValue($leiloesAntigos)
        );
        
        $encerrador = new EncerradorDeLeilao($daoFalso,$carteiroFalso);
        $encerrador->encerra();
         
        $encerrados = $daoFalso->encerrados();
       
        $this->assertTrue($leilao1->isEncerrado());
        $this->assertTrue($leilao2->isEncerrado());
        $this->assertEquals(2, $encerrador->getTotalEncerrados());
    }
    
    public function testNãoDeveEncerrarLeiloesQueComecaramHaMenosDeUmaSemanaAtras() {
        
        $antiga = new DateTime();
        
        date_sub($antiga, new DateInterval('P1D'));
        
        $leilao1 = new CriadorDeLeilao();
        $leilao1 = $leilao1->para("TV de plasma")
        ->naData($antiga)->constroi();
        
        $leilao2 = new CriadorDeLeilao();
        $leilao2 = $leilao2->para("Geladeira")
        ->naData($antiga)->constroi();
        
        $leiloesAntigos = array($leilao1,$leilao2);
        
        $daoFalso = $this->createMock(RepositorioDeLeiloes::class);
        $carteiroFalso = $this->createMock(EnviadorDeEmail::class);
        
        $daoFalso->method('correntes')->will(
            $this->returnValue($leiloesAntigos)
        );
        
        $encerrador = new EncerradorDeLeilao($daoFalso,$carteiroFalso);
        $encerrador->encerra();
        
        $encerrados = $daoFalso->encerrados();
        
        $this->assertTrue(!$leilao1->isEncerrado());
        $this->assertTrue(!$leilao2->isEncerrado());
        $this->assertEquals(0, $encerrador->getTotalEncerrados());
    }

    public function testDeveAtualizarLeiloesEncerrados() {
        
        $antiga = new DateTime("1999-1-20");
        
        $leilao1 = new CriadorDeLeilao();
        $leilao1 = $leilao1->para("TV de plasma")
        ->naData($antiga)->constroi();
        
        $daoFalso = $this->createMock(RepositorioDeLeiloes::class);
        $carteiroFalso = $this->createMock(EnviadorDeEmail::class);
        
        $daoFalso->expects($this->exactly(1))
            ->method("atualiza");

        $daoFalso->method('correntes')->will(
            $this->returnValue(array($leilao1))
        );
        
        $encerrador = new EncerradorDeLeilao($daoFalso,$carteiroFalso);
        $encerrador->encerra();
    }
    
    public function testDeveContinuarAExecucaoMesmoQuandoDaoFalha() {
        $antiga = new DateTime("1999-1-20");
        
        $leilao1 = new CriadorDeLeilao();
        $leilao1 = $leilao1->para("TV de plasma")
        ->naData($antiga)->constroi();
        
        $leilao2 = new CriadorDeLeilao();
        $leilao2 = $leilao2->para("Geladeira")
        ->naData($antiga)->constroi();
        
        $daoFalso = $this->createMock(RepositorioDeLeiloes::class);
        
        $daoFalso->method('correntes')->will(
            $this->returnValue(array($leilao1,$leilao2))
        );
        
        $daoFalso->method('atualiza')->will(
            $this->throwException(new RuntimeException())
        );
        
        $carteiroFalso = $this->createMock(EnviadorDeEmail::class);
        
        $carteiroFalso->method('envia')->will(
            $this->throwException(new RuntimeException())
        );
        
        $encerrador = new EncerradorDeLeilao($daoFalso, $carteiroFalso);
        
        $encerrador->encerra();
    }
    
    public function testDeveContinuarAExecucaoMesmoQuandoEnviadorFalha() {
        $antiga = new DateTime("1999-1-20");
        
        $leilao1 = new CriadorDeLeilao();
        $leilao1 = $leilao1->para("TV de plasma")
        ->naData($antiga)->constroi();
        
        $leilao2 = new CriadorDeLeilao();
        $leilao2 = $leilao2->para("Geladeira")
        ->naData($antiga)->constroi();
        
        $daoFalso = $this->createMock(RepositorioDeLeiloes::class);
        
        $daoFalso->method('atualiza')->will(
            $this->throwException(new RuntimeException())
            );
        
        $carteiroFalso = $this->createMock(EnviadorDeEmail::class);
        
        $carteiroFalso->method('envia')->will(
            $this->throwException(new RuntimeException())
            );
        
        $encerrador = new EncerradorDeLeilao($daoFalso, $carteiroFalso);
        
        $encerrador->encerra();
    }
    
    public function testNãoDeveExecutarCarteiroQuandoDaoFalha() {
        $antiga = new DateTime("1999-1-20");
        
        $leilao1 = new CriadorDeLeilao();
        $leilao1 = $leilao1->para("TV de plasma")
        ->naData($antiga)->constroi();
        
        $leilao2 = new CriadorDeLeilao();
        $leilao2 = $leilao2->para("Geladeira")
        ->naData($antiga)->constroi();
        
        $daoFalso = $this->createMock(RepositorioDeLeiloes::class);
        
        $daoFalso->method('correntes')->will(
            $this->returnValue(array($leilao1,$leilao2))
        );
        
        $daoFalso->method('atualiza')->will(
            $this->throwException(new RuntimeException())
        );
        
        $carteiroFalso = $this->createMock(EnviadorDeEmail::class);
        
        $carteiroFalso->expects($this->never())->method('envia');
        
        $encerrador = new EncerradorDeLeilao($daoFalso, $carteiroFalso);
        
        $encerrador->encerra();
    }
}