<?php
namespace test;

use PHPUnit_Extensions_Selenium2TestCase;

class DetalhesDoLeilaoPage
{

    private $pagina;

    public function __construct(PHPUnit_Extensions_Selenium2TestCase $pagina)
    {
        $this->pagina = $pagina;
    }

    public function lance($usuario, $valor)
    {
        $txtValor = $this->pagina->byName("lance.valor");
        
        $select = $this->pagina->select($this->pagina->byName("lance.usuario.id"));
        $select->selectOptionByLabel($usuario);
        
        $txtValor->value($valor);
        
        $this->pagina->byId("btnDarLance")->click();
    }
    
    public function existeLance($usuario, $valor)
    {
        $this->pagina->waitUntil(function($pagina) {    
            return $pagina->byId("lancesDados");
        },10000);
        
        return strpos($this->pagina->source(),$usuario) !== false
            && strpos($this->pagina->source(),strval($valor)) !== false;
    }
}

