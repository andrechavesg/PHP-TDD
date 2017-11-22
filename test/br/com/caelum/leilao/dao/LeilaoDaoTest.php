<?php
namespace test\br\com\caelum\leilao\dao;

include ('vendor/autoload.php');


use PHPUnit\Framework\TestCase;
use DateInterval;
use DateTime;
use src\br\com\caelum\leilao\Factory\ConnectionFactory;
use src\br\com\caelum\leilao\dao\LeilaoDao;
use src\br\com\caelum\leilao\dao\UsuarioDao;
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
}

