<?php
namespace src\test\br\com\caelum\leilao\dao;

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use DateInterval;
use DateTime;
use src\br\com\caelum\leilao\Factory\ConnectionFactory;
use src\br\com\caelum\leilao\dao\LeilaoDao;
use src\br\com\caelum\leilao\dao\UsuarioDao;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;

class LeilaoDaoTest extends TestCase
{   
    private $conexao;
    private $leilaoDao;
    private $usuarioDao;
    
    /**
    * @before
    */
    public function antes()
    {
        $this->conexao = ConnectionFactory::getConnection();
        $this->leilaoDao = new LeilaoDao($this->conexao);
        $this->usuarioDao = new UsuarioDao($this->conexao);
        
        $this->conexao->beginTransaction();
    }
    
    /**
     * @after
     */
    public function depois()
    {
        $this->conexao->rollBack();
    }
    
    public function testDeveContarLeiloesNaoEncerrados()
    {
        $mauricio = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $ativo = new Leilao("Geladeira", 1500.0, $mauricio, false);
        $encerrado = new Leilao("XBox", 700.0, $mauricio, false);
        $encerrado->encerra();
        
        $this->usuarioDao->salvar($mauricio);
        $this->leilaoDao->salvar($ativo);
        $this->leilaoDao->salvar($encerrado);
        
        $total = $this->leilaoDao->total();
        
        $this->assertEquals(1, $total);
    }
    
    public function testNãoDeveContarLeiloesNaoEncerrados()
    {
        $mauricio = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $ativo = new Leilao("Geladeira", 1500.0, $mauricio, false);
        $encerrado = new Leilao("XBox", 700.0, $mauricio, false);
        
        $this->usuarioDao->salvar($mauricio);
        $this->leilaoDao->salvar($ativo);
        $this->leilaoDao->salvar($encerrado);
        
        $total = $this->leilaoDao->total();
        
        $this->assertEquals(2, $total);
    }
    
    public function testDeveConterLeilaoNovo()
    {
        $mauricio = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $leilaoUsado = new Leilao("Geladeira", 1500.0, $mauricio);
        $leilaoUsado->setUsado(true);
        
        $leilaoNovo = new Leilao("XBox", 700.0, $mauricio);
        $leilaoNovo->setUsado(false);
        
        $this->usuarioDao->salvar($mauricio);
        $this->leilaoDao->salvar($leilaoUsado);
        $this->leilaoDao->salvar($leilaoNovo);
        
        $novos = $this->leilaoDao->novos();
        
        $this->assertEquals(1,count($novos));
        $this->assertEquals($leilaoNovo->getId(), $novos[0]->getId());
    }
    
    public function testDeveConterLeilaoAntigo()
    {
        $seteDiasAtras = new DateTime();
        $seteDiasAtras->sub(new DateInterval("P7D"));
        
        $mauricio = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $leilaoRecente = new Leilao("Geladeira", 1500.0, $mauricio);
        $leilaoRecente->setDataAbertura(new DateTime());
        
        $leilaoAntigo = new Leilao("XBox", 700.0, $mauricio);
        $leilaoAntigo->setDataAbertura($seteDiasAtras);
        $this->usuarioDao->salvar($mauricio);
        $this->leilaoDao->salvar($leilaoRecente);
        $this->leilaoDao->salvar($leilaoAntigo);
        
        $antigos = $this->leilaoDao->antigos();
        
        $this->assertEquals(1,count($antigos));
        $this->assertEquals($leilaoAntigo->getId(), $antigos[0]->getId());
    }
    
    public function testDeveTrazerLeiloesNaoEncerradosNoPeriodo()
    {
        $comecoDoIntervalo = new DateTime();
        $comecoDoIntervalo->sub(new DateInterval("P10D"));
        
        $fimDoIntervalo = new DateTime();
        $dataDoLeilao1 = new DateTime();
        
        $dataDoLeilao1->sub(new dateInterval("P2D"));
        $dataDoLeilao2 = new DateTime();
        $dataDoLeilao2->sub(new DateInterval("P20D"));
        
        $mauricio = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $leilao1 = new Leilao("XBox", 700.0, $mauricio, false);
        $leilao1->setDataAbertura($dataDoLeilao1);
        
        $leilao2 = new Leilao("Geladeira", 1700.0, $mauricio, false);
        $leilao2->setDataAbertura($dataDoLeilao2);
        
        $this->usuarioDao->salvar($mauricio);
        $this->leilaoDao->salvar($leilao1);
        $this->leilaoDao->salvar($leilao2);
        
        $leiloes = $this->leilaoDao->porPeriodo($comecoDoIntervalo, $fimDoIntervalo);
        
        $this->assertEquals(1, count($leiloes));
        $this->assertEquals("XBox", $leiloes[0]->getNome());
    }
    
    public function testeNaoDeveTrazerLeiloesEncerradosNoPeriodo()
    {
        $comecoDoIntervalo = new DateTime();
        $comecoDoIntervalo->sub(new DateInterval("P10D"));
        $fimDoIntervalo = new DateTime();
        $dataDoLeilao1 = new DateTime();
        $dataDoLeilao1->sub(new DateInterval("P2D"));
        
        $mauricio = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $leilao1 = new Leilao("XBox", 700.0, $mauricio);
        $leilao1->setDataAbertura($dataDoLeilao1);
        $leilao1->encerra();
        
        $this->usuarioDao->salvar($mauricio);
        $this->leilaoDao->salvar($leilao1);
        
        $leiloes = $this->leilaoDao->porPeriodo($comecoDoIntervalo, $fimDoIntervalo);
        
        $this->assertEquals(0, count($leiloes));
    }
    
    public function testDeveTrazerLeiloesNaoEncerradosDentroDoIntervalo()
    {
        $comecoDoIntervalo = 500;
        $fimDoIntervalo = 1000;
        
        $mauricio = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        $andre = new Usuario("Andre Chaves", "andre@chaves.com.br");
        
        $leilao1 = new Leilao("XBox", 700.0, $mauricio);
        
        $lance1 = new Lance($andre,710,$leilao1);
        $lance2 = new Lance($mauricio,720,$leilao1);
        $lance3 = new Lance($andre, 730,$leilao1);
        
        $leilao1->setLances(array($lance1,$lance2,$lance3));
        $this->usuarioDao->salvar($mauricio);
        $this->leilaoDao->salvar($leilao1);
        
        $leiloes = $this->leilaoDao->disputadosEntre($comecoDoIntervalo, $fimDoIntervalo);
        
        $this->assertEquals(1, count($leiloes));
    }
    
    public function testNaoDeveTrazerLeiloesEncerradosDentroDoIntervalo()
    {
        $comecoDoIntervalo = 500;
        $fimDoIntervalo = 1000;
        
        $mauricio = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $leilao1 = new Leilao("XBox", 700.0, $mauricio);
        $leilao1->encerra();
        
        $this->usuarioDao->salvar($mauricio);
        $this->leilaoDao->salvar($leilao1);
        
        $leiloes = $this->leilaoDao->disputadosEntre($comecoDoIntervalo, $fimDoIntervalo);
        
        $this->assertEquals(0, count($leiloes));
    }
    
    public function testNaoDeveTrazerLeiloesEncerradosForaDoIntervalo()
    {
        $comecoDoIntervalo = 1000;
        $fimDoIntervalo = 1500;
        
        $mauricio = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $leilao1 = new Leilao("XBox", 700.0, $mauricio);
        $leilao1->encerra();
        
        $this->usuarioDao->salvar($mauricio);
        $this->leilaoDao->salvar($leilao1);
        
        $leiloes = $this->leilaoDao->disputadosEntre($comecoDoIntervalo, $fimDoIntervalo);
        
        $this->assertEquals(0, count($leiloes));
    }
    
    public function testNaoDeveTrazerLeiloesNaoEncerradosForaDoIntervalo()
    {
        $comecoDoIntervalo = 1000;
        $fimDoIntervalo = 1500;
        
        $mauricio = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $leilao1 = new Leilao("XBox", 700.0, $mauricio);
        $leilao1->encerra();
        
        $this->usuarioDao->salvar($mauricio);
        $this->leilaoDao->salvar($leilao1);
        
        $leiloes = $this->leilaoDao->disputadosEntre($comecoDoIntervalo, $fimDoIntervalo);
        
        $this->assertEquals(0, count($leiloes));
    }
    
    public function testDeveDevolverLeiloesQueOUsuarioDeuPeloMenosUmLance()
    {
        $mauricio = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $leilao1 = new Leilao("XBox", 700.0, $mauricio);
        $lance1 = new Lance($mauricio,710.0,$leilao1);
        $leilao1->setLances(array($lance1));
        
        $leilao2 = new Leilao("Playstation", 500.0, $mauricio);
        $lance2 = new Lance($mauricio,550.0,$leilao2);
        $leilao2->setLances(array($lance2));
        
        $this->usuarioDao->salvar($mauricio);
        $this->leilaoDao->salvar($leilao1);
        $this->leilaoDao->salvar($leilao2);
        
        $leiloes = $this->leilaoDao->listaLeiloesDoUsuario($mauricio);
        
        $this->assertEquals(2, count($leiloes));
    }
    
    public function testDeveRetornarValorInicialMedioDosLeiloesQueOUsuarioDeuPeloMenosUmLance()
    {
        $mauricio = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $leilao1 = new Leilao("Celular", 700.0, $mauricio);
        $lance1 = new Lance($mauricio,710.0,$leilao1);
        $leilao1->setLances(array($lance1));
        
        $leilao2 = new Leilao("Geladeira", 1300.0, $mauricio);
        $lance2 = new Lance($mauricio,550.0,$leilao2);
        $leilao2->setLances(array($lance2));
        
        $this->usuarioDao->salvar($mauricio);
        $this->leilaoDao->salvar($leilao1);
        $this->leilaoDao->salvar($leilao2);
        
        $valorMedio = $this->leilaoDao->getValorInicialMedioDoUsuario($mauricio);
        
        $this->assertEquals(1000.0, $valorMedio);
    }
    
    public function testDeveDeletarEncerrados()
    {
        $mauricio = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $leilao = new Leilao("XBox", 700.0, $mauricio);
        $leilao->encerra();
        
        $this->leilaoDao->salvar($leilao);
        
        $this->leilaoDao->deletaEncerrados();
        $leilaoNoBanco = $this->leilaoDao->porId($leilao->getId());
        $this->assertFalse($leilaoNoBanco);
    }
}