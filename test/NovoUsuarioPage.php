<?php
namespace test;

use PHPUnit_Extensions_Selenium2TestCase;

class NovoUsuarioPage
{
    private $pagina;
    
    public function __construct(PHPUnit_Extensions_Selenium2TestCase $pagina)
    {
        $this->pagina = $pagina;
    }
    
    public function cadastra($nome, $email)
    {
        $txtNome = $this->pagina->byName("usuario.nome");
        $txtEmail = $this->pagina->byName("usuario.email");
        
        $txtNome->value($nome);
        $txtEmail->value($email);
        
        $txtNome->submit();
    }
    
    public function exibiuErroDoNome()
    {
        return strpos($this->pagina->source(),"Nome obrigatorio!") !== false;
    }
    
    public function exibiuErroDoNomeEDoEmail()
    {
        return strpos($this->pagina->source(),"Nome obrigatorio!") !== false &&
            strpos($this->pagina->source(),"E-mail obrigatorio!");
    }
}