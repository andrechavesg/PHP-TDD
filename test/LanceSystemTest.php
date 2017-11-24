<?php
namespace test;

use PHPUnit_Extensions_Selenium2TestCase;

class LanceSystemTest extends PHPUnit_Extensions_Selenium2TestCase
{
    private $lances;
    
    /**
     * @before
     */
    protected function setUp()
    {
        $this->setBrowserUrl("http://localhost:8080");
        
        $this->lances = new DetalhesDoLeilaoPage($this);
        
    }
    
    public function testDeveFazerUmLance() {
        $criadorDeCenarios = new CriadorDeCenarios($this);
        
        $criadorDeCenarios->umUsuario("Paulo Henrique", "paulo@henrique.com")
            ->umUsuario("José Alberto", "jose@alberto.com")
            ->umLeilao("Paulo Henrique", "Geladeira", 100, false)
            ->umLance("José Alberto", 150);
        
        $this->assertTrue($this->lances->existeLance("José Alberto", 150));
    }
    
    /**
     * @after
     */
    public function limpa()
    {
        $this->url("http://localhost:8080/apenas-teste/limpa");
    }
}

