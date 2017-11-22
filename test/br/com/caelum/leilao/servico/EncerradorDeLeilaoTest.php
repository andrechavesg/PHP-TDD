<?php
namespace test\br\com\caelum\leilao\servico;

require_once 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use DateTime;
use src\br\com\caelum\leilao\DAO\RepositorioDeLeiloes;
use src\br\com\caelum\leilao\servico\CriadorDeLeilao;
use src\br\com\caelum\leilao\servico\EncerradorDeLeilao;

class EncerradorDeLeilaoTest extends TestCase
{
    
    public function testDeveEncerrarLeiloesQueComecaramUmaSemanaAtras()
    {
        $antiga = new DateTime('1999-01-20');
        
        $leilao1 = new CriadorDeLeilao();
        $leilao1 = $leilao1->para("TV de plasma")
            ->naData($antiga)->constroi();
        
        $leilao2 = new CriadorDeLeilao();
        $leilao2 = $leilao2->para("Geladeira")
            ->naData($antiga)->constroi();
        
        $leiloesAntigos = array($leilao1,$leilao2);
        
        $daoFalso = $this->createMock(RepositorioDeLeiloes::class);
        
        $daoFalso->method('correntes')->will(
            $this->returnValue($leiloesAntigos)
        );
        
        $encerrador = new EncerradorDeLeilao($daoFalso);
        $encerrador->encerra();
         
        $encerrados = $daoFalso->encerrados();
       
        $this->assertTrue($leilao1->isEncerrado());
        $this->assertTrue($leilao2->isEncerrado());
        $this->assertEquals(2, $encerrador->getTotalEncerrados());
    }
    
    public function testNãoDeveEncerrarLeiloesQueComecaramHaMenosDeUmaSemanaAtras() {
        
        $antiga = new DateTime();
        
        date_sub($antiga, date_interval_create_from_date_string('1 days'));
        
        
        $leilao1 = new CriadorDeLeilao();
        $leilao1 = $leilao1->para("TV de plasma")
        ->naData($antiga)->constroi();
        
        $leilao2 = new CriadorDeLeilao();
        $leilao2 = $leilao2->para("Geladeira")
        ->naData($antiga)->constroi();
        
        $leiloesAntigos = array($leilao1,$leilao2);
        
        $daoFalso = $this->createMock(RepositorioDeLeiloes::class);
        
        $daoFalso->method('correntes')->will(
            $this->returnValue($leiloesAntigos)
            );
        
        $encerrador = new EncerradorDeLeilao($daoFalso);
        $encerrador->encerra();
        
        $encerrados = $daoFalso->encerrados();
        
        $this->assertTrue(!$leilao1->isEncerrado());
        $this->assertTrue(!$leilao2->isEncerrado());
        $this->assertEquals(0, $encerrador->getTotalEncerrados());
    }
}