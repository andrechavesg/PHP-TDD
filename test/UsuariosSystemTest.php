<?php
namespace test;

use PHPUnit_Extensions_Selenium2TestCase;

class UsuariosSystemTest extends PHPUnit_Extensions_Selenium2TestCase
{
    private $usuarios;
    
    /**
     * @before
     */
    protected function setUp()
    {
        $url = new URLDaAplicacao();
        
        $this->setBrowserUrl($url->getUrlBase());
        
        $this->usuarios = new UsuariosPage($this);
    }
    
    public function testDeveAdicionarUmUsuario()
    {
        $this->usuarios->visita();
        $this->usuarios->novo()
            ->cadastra("Ronaldo Luiz de Albuquerque", "ronaldo2009@terra.com.br");
        
        $this->assertTrue($this->usuarios->existeNaListagem(
            "Ronaldo Luiz de Albuquerque", "ronaldo2009@terra.com.br"));
    }
    
    public function testNaoDeveAdicionarUmUsuarioSemNome()
    {
        $this->usuarios->visita();
        $novoUsuario = $this->usuarios->novo();
        $novoUsuario->cadastra("", "ronaldo2009@terra.com.br");
        
        $this->assertTrue($novoUsuario->exibiuErroDoNome());
    }
    
    public function testNaoDeveAdicionarUmUsuarioSemNomeOuSemEmail()
    {
        $this->usuarios->visita();
        $novoUsuario = $this->usuarios->novo();
        $novoUsuario->cadastra("", "");
        
        $this->assertTrue($novoUsuario->exibiuErroDoNomeEDoEmail());
    }
    
    public function testDeveRemoverUmUsuario()
    {
        $this->usuarios->visita();
        $this->usuarios->novo()->cadastra("novo","novo@novo.com");
        $this->usuarios->remove();
       
        $this->assertTrue(!$this->usuarios->existeNaListagem("novo", "novo@novo.com"));
    }
    
    public function testDeveEditarUmUsuario()
    {
        $this->usuarios->visita();
        $this->usuarios->novo()->cadastra("novo","novo@novo.com");
        $this->usuarios->edita()->atualiza("novo nome","novo@email.com");
        
        $this->assertTrue($this->usuarios->existeNaListagem("novo nome", "novo@email.com"));
    }
    
    /**
     * @after
     */
    public function limpa()
    {
        $this->url("http://localhost:8080/apenas-teste/limpa");
    }
}