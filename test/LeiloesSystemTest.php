<?php
namespace test;

use PHPUnit_Extensions_Selenium2TestCase;

class LeiloesSystemTest extends PHPUnit_Extensions_Selenium2TestCase
{
    private $leiloes;
    
    /**
     * @before
     */
    protected function setUp()
    {
        $this->setBrowserUrl("http://localhost:8080");
        
        $this->usuarios = new UsuariosPage($this);
        
        $this->leiloes = new LeiloesPage($this);
    }
    
    public function testDeveCadastrarUmLeilao()
    {
        $this->usuarios->visita();
        $this->usuarios->novo()->cadastra("Paulo Henrique", "paulo@henrique.com");
        
        $this->leiloes->visita();
        
        $novoLeilao = $this->leiloes->novo();
        $novoLeilao->preenche("celular", 500, "Paulo Henrique", true);
        
        $this->assertTrue($this->leiloes->existe("Geladeira", 500, "Paulo Henrique", true));
    }
    
    public function testNaoDeveAdicionarSemNomeOuValorInicial()
    {
        $this->usuarios->visita();
        $this->usuarios->novo()->cadastra("Paulo Henrique", "paulo@henrique.com");
        
        $this->leiloes->visita();
        
        $novoLeilao = $this->leiloes->novo();
        $novoLeilao->preenche("", 0, "Paulo Henrique", true);
        
        $this->assertTrue($novoLeilao->exibeErroDoNomeEDoValor());
    }
    
    /**
     * @after
     */
    public function limpa()
    {
        $this->url("http://localhost:8080/apenas-teste/limpa");
    }
}

