<?php
namespace test\br\com\caelum\leilao\dao;

include ('vendor/autoload.php');

use PHPUnit\Framework\TestCase;
use src\br\com\caelum\leilao\Factory\ConnectionFactory;
use src\br\com\caelum\leilao\dao\UsuarioDao;
use src\br\com\caelum\leilao\dominio\Usuario;

class UsuarioDaoTest extends TestCase
{   
    public function testDeveEncontrarPeloNomeEEmailMockado()
    {
        
        $usuario = new Usuario("João da Silva", "joao@dasilva.com.br");
        
        $pdo = ConnectionFactory::getConnection();
        
        $usuarioDao = new UsuarioDao($pdo);
        
        $usuarioDao->salvar($usuario);
        
        $usuarioDoBanco = $usuarioDao->porNomeEEmail("João da Silva", "joao@dasilva.com.br");
        
        $this->assertEquals($usuario->getNome(), $usuarioDoBanco->getNome());
        $this->assertEquals($usuario->getEmail(), $usuarioDoBanco->getEmail());
        
        $usuarioDao->deletar($usuarioDoBanco);
    }
    
    public function testDeveRetornarFalseParaUsuarioNaoExistente()
    {
        $usuario = new Usuario("João da Silva", "joao@dasilva.com.br");
        
        $pdo = ConnectionFactory::getConnection();
        
        $usuarioDao = new UsuarioDao($pdo);
        
        $usuarioDoBanco = $usuarioDao->porNomeEEmail("João da Silva", "joao@dasilva.com.br");
        
        $this->assertFalse($usuarioDoBanco);
    }
}

