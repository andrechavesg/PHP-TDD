<?php
namespace test;

use PHPUnit_Extensions_Selenium2TestCase;

class UsuariosSystemTest extends PHPUnit_Extensions_Selenium2TestCase
{
    /**
     * @before
     */
    protected function setUp()
    {
        $this->setBrowserUrl("http://localhost:8080/usuarios");
    }
    
    public function testDeveAdicionarUmUsuario()
    {
        $this->url('/new');
        $nome = $this->byName("usuario.nome");
        $email = $this->byName("usuario.email");
        
        $nome->value("Ronaldo Luiz de Albuquerque");
        $email->value("ronaldo2009@terra.com.br");
        
        $email->submit();
        
        $achouNome = strpos($this->source(),"Ronaldo Luiz de Albuquerque") !== false;
        $achouEmail = strpos($this->source(),"ronaldo2009@terra.com.br") !== false;
        
        $this->assertTrue($achouNome);
        $this->assertTrue($achouEmail);
    }
    
    public function testNaoDeveAdicionarUmUsuarioSemNome()
    {
        $this->url('/new');
        $nome = $this->byName("usuario.nome");
        $email = $this->byName("usuario.email");
        
        $nome->value("");
        $email->value("ronaldo2009@terra.com.br");
        
        $email->submit();
        
        $exibiuErro = strpos($this->source(),"Nome obrigatorio!") !== false;
        
        $this->assertTrue($exibiuErro);
    }
    
    public function testNaoDeveAdicionarUmUsuarioSemNomeOuSemEmail()
    {
        $this->url('/new');
        $nome = $this->byName("usuario.nome");
        $email = $this->byName("usuario.email");
        
        $nome->value("");
        $email->value("");
        
        $email->submit();
        
        $exibiuErroParaNome = strpos($this->source(),"Nome obrigatorio!") !== false;
        $exibiuErroParaEmail = strpos($this->source(),"E-mail obrigatorio!") !== false;
        
        $this->assertTrue($exibiuErroParaNome);
        $this->assertTrue($exibiuErroParaEmail);
    }
    
    public function testDeveAbrirFormulario()
    {
        $link = $this->byLinkText("Novo Usuário");
        
        $link->click();
    }
}