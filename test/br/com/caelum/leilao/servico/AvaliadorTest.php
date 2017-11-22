<?php
namespace test\br\com\caelum\leilao\servico;

require_once ("vendor/autoload.php");

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\servico\Avaliador;
use src\br\com\caelum\leilao\servico\CriadorDeLeilao;

class AvaliadorTest extends TestCase
{

    private $leiloeiro;

    private $joao;

    private $maria;

    private $pedro;

    /**
     * @before
     */
    public function criaAvaliador()
    {
        $this->leiloeiro = new Avaliador();
        $this->joao = new Usuario("João");
        $this->maria = new Usuario("Maria");
        $this->pedro = new Usuario("Pedro");
    }

    public function testAvaliadorComLancesCrescentes()
    {
        $leilao = new Leilao("Playstation 3 Novo");
        
        $leilao->propoe(new Lance($this->joao, 300.0));
        $leilao->propoe(new Lance($this->pedro, 400.0));
        $leilao->propoe(new Lance($this->joao, 500.0));
        $leilao->propoe(new Lance($this->pedro, 600.0));
        $leilao->propoe(new Lance($this->joao, 700.0));
        
        $this->leiloeiro->avalia($leilao);
        
        $maiorEsperado = 700;
        $menorEsperado = 300;
        
        $this->assertEquals($maiorEsperado, $this->leiloeiro->getMaiorLance());
        $this->assertEquals($menorEsperado, $this->leiloeiro->getMenorLance());
    }

    public function testAvaliadorComLancesDecrescentes()
    {
        $leilao = new Leilao("Playstation 3 Novo");
        
        $leilao->propoe(new Lance($this->joao, 700.0));
        $leilao->propoe(new Lance($this->pedro, 600.0));
        $leilao->propoe(new Lance($this->joao, 500.0));
        $leilao->propoe(new Lance($this->pedro, 400.0));
        $leilao->propoe(new Lance($this->joao, 300.0));
        
        $this->leiloeiro->avalia($leilao);
        
        $this->assertEquals(700.0, $this->leiloeiro->getMaiorLance());
        $this->assertEquals(300.0, $this->leiloeiro->getMenorLance());
    }

    public function testAvaliadorComUmLance()
    {
        $leilao = new Leilao("Playstation 3 Novo");
        $leilao->propoe(new Lance($this->joao, 300.0));
        
        $this->leiloeiro->avalia($leilao);
        
        $this->assertEquals(300.0, $this->leiloeiro->getMaiorLance());
        $this->assertEquals(300.0, $this->leiloeiro->getMenorLance());
    }
    
    /**
     * @expectedException     RuntimeException
     */
    public function testNaoDeveAvaliarLeiloesSemNenhumLanceDado()
    {
        $criadorDeLeilao = new CriadorDeLeilao();
        $leilao = $criadorDeLeilao->para("Playstation 3 Novo")->constroi();
        
        $this->leiloeiro->avalia($leilao);
    }

    public function testDeveEncontrarOsTresMaioresLances()
    {
        $leilao = new CriadorDeLeilao();
        $leilao = $leilao->para("Playstation 3 Novo")
            ->lance($this->joao, 100.0)
            ->lance($this->maria, 200.0)
            ->lance($this->joao, 300.0)
            ->lance($this->maria, 400.0)
            ->constroi();
        
        $this->leiloeiro->avalia($leilao);
        
        $maiores = $this->leiloeiro->getTresMaiores();
        
        $this->assertEquals(3, count($maiores));
        $this->assertEquals(400.0, $maiores[0]->getValor(), 0.00001);
        $this->assertEquals(300.0, $maiores[1]->getValor(), 0.00001);
        $this->assertEquals(200.0, $maiores[2]->getValor(), 0.00001);
    }
}

