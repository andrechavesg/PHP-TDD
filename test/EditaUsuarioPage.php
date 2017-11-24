<?php
namespace test;

use PHPUnit_Extensions_Selenium2TestCase;

class EditaUsuarioPage
{
    private $pagina;
    
    public function __construct(PHPUnit_Extensions_Selenium2TestCase $pagina) {
        $this->pagina = $pagina;
    }
    
    public function atualiza($nome, $email)
    {
        $txtNome = $this->pagina->byName("usuario.nome");
        $txtEmail = $this->pagina->byName("usuario.email");
        
        $txtNome->clear();
        $txtNome->value($nome);
        
        $txtEmail->clear();
        $txtEmail->value($email);
        
        $txtNome->submit();
    }
}

