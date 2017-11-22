<?php
namespace test\br\com\caelum\leilao\dao;

require 'vendor/autoload.php';


use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;
use src\br\com\caelum\leilao\dao\UsuarioDao;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\Factory\ConnectionFactory;

class UsuarioDaoTest extends TestCase
{   
    private $conexao;
    private $usuarioDao;
    
    /**
     * @before
     */
    public function antes() {
        $this->conexao = ConnectionFactory::getConnection();
        $this->usuarioDao = new UsuarioDao($this->conexao);
        $this->conexao->beginTransaction();
    }
    
    /**
     * @after
     */
    public function depois() {
        $this->conexao->rollBack();    
    }
    
    public function testDeveEncontrarPeloNomeEEmailMockado()
    {
        
        $usuario = new Usuario("João da Silva", "joao@dasilva.com.br");
        
        $this->usuarioDao->salvar($usuario);
        
        $usuarioDoBanco = $this->usuarioDao->porNomeEEmail("João da Silva", "joao@dasilva.com.br");
        
        $this->assertEquals($usuario->getNome(), $usuarioDoBanco->getNome());
        $this->assertEquals($usuario->getEmail(), $usuarioDoBanco->getEmail());
    }
    
    public function testDeveRetornarFalseParaUsuarioNaoExistente()
    {
        $usuario = new Usuario("João da Silva", "joao@dasilva.com.br");
        
        $usuarioDoBanco = $this->usuarioDao->porNomeEEmail("João da Silva", "joao@dasilva.com.br");
        
        $this->assertFalse($usuarioDoBanco);
    }
    
    public function testDeveDeletarUmUsuario()
    {
        $usuario = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $this->usuarioDao->salvar($usuario);
        $this->usuarioDao->deletar($usuario);
        
        $usuarioNoBanco = $this->usuarioDao->porNomeEEmail("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $this->assertFalse($usuarioNoBanco);
    }
    
    public function testDeveAtualizarUmUsuario()
    {
        $usuario = new Usuario("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $this->usuarioDao->salvar($usuario);
        
        $usuario->setNome("Andre Chaves");
        $usuario->setEmail("andre@chaves.com.br");
        
        $this->usuarioDao->atualizar($usuario);
        
        $usuarioNovoNoBanco = $this->usuarioDao->porNomeEEmail("Andre Chaves", "andre@chaves.com.br");
        $usuarioAntigoNoBanco = $this->usuarioDao->porNomeEEmail("Mauricio Aniche", "mauricio@aniche.com.br");
        
        $this->assertEquals($usuario->getId(),$usuarioNovoNoBanco->getId());
        $this->assertFalse($usuarioAntigoNoBanco);
    }
}

