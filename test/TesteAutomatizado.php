<?php
namespace test;

use PHPUnit_Extensions_Selenium2TestCase;

require_once "vendor/autoload.php";

class TesteAutomatizado extends PHPUnit_Extensions_Selenium2TestCase
{
    /**
     * @before
     */
    protected function setUp()
    {
        $this->setBrowserUrl("http://google.com/");
    }
    
    public function testTitle()
    {
        $this->url('http://www.google.com/');
        
        $campoDeTexto = $this->byName("q");
        $campoDeTexto->value("Caelum");
        $campoDeTexto->submit();
    }
}