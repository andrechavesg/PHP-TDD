<?php
namespace test;

use PHPUnit_Extensions_Selenium2TestCase;

class CriadorDeCenarios
{
    private $pagina;
    
    public function __construct(PHPUnit_Extensions_Selenium2TestCase $pagina) {
        $this->pagina = $pagina;
    }
    
    public function umUsuario($nome, $email) {
        $usuarios = new UsuariosPage($this->pagina);
        $usuarios->visita();
        $usuarios->novo()->cadastra($nome, $email);
        
        return $this;
    }
    
    public function umLeilao($usuario,
        $produto,
        $valor,
        $usado) {
            $leiloes = new LeiloesPage($this->pagina);
            $leiloes->visita();
            $leiloes->novo()->preenche($produto, $valor, $usuario, $usado);
            
            return $this;
    }
    
    public function umLance($usuario,$valor){
        $leiloes = new LeiloesPage($this->pagina);
        
        $lances = $leiloes->detalhes(1);
        
        $lances->lance($usuario, $valor);
    }
}

