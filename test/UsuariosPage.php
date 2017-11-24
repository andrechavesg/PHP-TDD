<?php
namespace test;

use PHPUnit_Extensions_Selenium2TestCase;

class UsuariosPage
{
    
    private $pagina;
    
    public function __construct(PHPUnit_Extensions_Selenium2TestCase $pagina) {
        $this->pagina = $pagina;
    }
    
    public function visita()
    {
        $this->pagina->url("");
    }
    
    public function novo()
    {
        // clica no link de novo usuario
        $this->pagina->byLinkText("Novo Usuário")->click();
        // retorna a classe que representa a nova pagina
        return new NovoUsuarioPage($this->pagina);
    }
    
    public function remove()
    {
        $this->pagina->byTag("button")->click();
        $this->pagina->acceptAlert();
    }
    
    public function existeNaListagem($nome, $email)
    {
        // verifica se ambos existem na listagem
        return strpos($this->pagina->source(),$nome) !== false 
            && strpos($this->pagina->source(),$email) !== false;
    }
}

