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
        $this->setBrowserUrl("http://localhost:8080/usuarios");
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
        $this->usuarios->novo()
            ->cadastra("", "ronaldo2009@terra.com.br");
        
        $this->assertTrue($this->pagina->exibiuErroDoNome());
    }
    
    public function testNaoDeveAdicionarUmUsuarioSemNomeOuSemEmail()
    {
        $this->usuarios->visita();
        $this->usuarios->novo()
           ->cadastra("", "");
        
        $this->assertTrue($this->pagina->exibiuErroDoNomeEDoEmail());
    }
    
    public function testDeveRemoverUmUsuario()
    {
        $this->usuarios->visita();
        $this->usuarios->remove();
    }
}