<?php
namespace test\br\com\caelum\leilao;

require_once "vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\servico\Avaliador;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;

class AvaliadorTest extends TestCase
{
    public function testDeveEntenderLancesEmOrdemCrescente()
    {
        // 3: cenario lances em ordem crescente
        $joao = new Usuario("Joao");
        $jose = new Usuario("José");
        $maria = new Usuario("Maria");
        
        $leilao = new Leilao("Playstation 3 Novo");
        
        $leilao->propoe(new Lance($maria, 250.0));
        $leilao->propoe(new Lance($joao, 300.0));
        $leilao->propoe(new Lance($jose, 400.0));
        
        // executando a acao
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        // comparando a saida com o esperado
        $this->assertEquals(400, $leiloeiro->getMaiorLance(), 0.0001);
        $this->assertEquals(250, $leiloeiro->getMenorLance(), 0.0001);
    }
    
    public function testDeveEntenderLancesEmOrdemCrescenteComValoresMaiores()
    {
        // 3: cenario lances em ordem crescente
        $joao = new Usuario("Joao");
        $jose = new Usuario("José");
        $maria = new Usuario("Maria");
        
        $leilao = new Leilao("Playstation 3 Novo");
        
        $leilao->propoe(new Lance($maria, 1000.0));
        $leilao->propoe(new Lance($joao, 2000.0));
        $leilao->propoe(new Lance($jose, 3000.0));
        
        // executando a acao
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        // comparando a saida com o esperado
        $this->assertEquals(3000.0, $leiloeiro->getMaiorLance(), 0.0001);
        $this->assertEquals(1000.0, $leiloeiro->getMenorLance(), 0.0001);
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
    
    public function testDeveEntenderLeilaoComApenasUmLance()
    {
        $joao = new Usuario("Joao");
        $leilao = new Leilao("Playstation 3 Novo");
        
        $leilao->propoe(new Lance($joao, 1000.0));
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $this->assertEquals(1000, $leiloeiro->getMaiorLance(), 0.0001);
        $this->assertEquals(1000, $leiloeiro->getMenorLance(), 0.0001);
    }
    
    public function testAvaliadorSemLances() {
        $joao = new Usuario("Joao");
        
        $leilao = new Leilao("Playstation 1 Usado");
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $this->assertEquals(0,$leiloeiro->getMaiorLance());
        $this->assertEquals(0,$leiloeiro->getMenorLance());
    }
    
    public function testDeveEncontrarOsTresMaioresLances()
    {
        $joao = new Usuario("João");
        $maria = new Usuario("Maria");
        $leilao = new Leilao("Playstation 3 Novo");
        
        $leilao->propoe(new Lance($joao, 100.0));
        $leilao->propoe(new Lance($maria, 200.0));
        $leilao->propoe(new Lance($joao, 300.0));
        $leilao->propoe(new Lance($maria, 400.0));
        
        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
        
        $maiores = $leiloeiro->getTresMaiores();
        
        $this->assertEquals(3, count($maiores));
        $this->assertEquals(400, $maiores[0]->getValor(), 0.00001);
        $this->assertEquals(300, $maiores[1]->getValor(), 0.00001);
        $this->assertEquals(200, $maiores[2]->getValor(), 0.00001);
    }
}

