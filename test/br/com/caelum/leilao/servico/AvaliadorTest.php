<?php
namespace test\br\com\caelum\servico;

require_once("vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\servico\Avaliador;
/**
 *  test case.
 */
class AvaliadorTest extends TestCase
{
    public function testAvaliadorComLancesCrescentes() {
        $joao = new Usuario("Joao");
        $pedro = new Usuario("Pedro");
        
        $leilao = new Leilao("Playstation 3 Novo");
        
        $leilao->propoe(new Lance($joao,300.0));
        $leilao->propoe(new Lance($pedro,400.0));
        $leilao->propoe(new Lance($joao,500.0));
        $leilao->propoe(new Lance($pedro,600.0));
        $leilao->propoe(new Lance($joao,700.0));
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $maiorEsperado = 700;
        $menorEsperado = 300;
        
        $this->assertEquals($maiorEsperado, $leiloeiro->getMaiorLance());
        $this->assertEquals($menorEsperado, $leiloeiro->getMenorLance());
    }
    
    public function testAvaliadorComLancesDecrescentes() {
        $joao = new Usuario("Joao");
        $pedro = new Usuario("Pedro");
        
        $leilao = new Leilao("Playstation 3 Novo");
        
        $leilao->propoe(new Lance($joao,700.0));
        $leilao->propoe(new Lance($pedro,600.0));
        $leilao->propoe(new Lance($joao,500.0));
        $leilao->propoe(new Lance($pedro,400.0));
        $leilao->propoe(new Lance($joao,300.0));
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);

        $this->assertEquals(700.0,$leiloeiro->getMaiorLance());
        $this->assertEquals(300.0,$leiloeiro->getMenorLance());
    }
    
    public function testAvaliadorComUmLance() {
        $joao = new Usuario("Joao");
        $leilao = new Leilao("Playstation 3 Novo");
        
        $leilao->propoe(new Lance($joao,300.0));
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $this->assertEquals(300.0,$leiloeiro->getMaiorLance());
        $this->assertEquals(300.0,$leiloeiro->getMenorLance());   
    }
    
    public function testAvaliadorSemLances() {
        $joao = new Usuario("Joao");
        
        $leilao = new Leilao("Playstation 1 Usado");
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $this->assertEquals(0,$leiloeiro->getMaiorLance());
        $this->assertEquals(0,$leiloeiro->getMenorLance());
    }
}

