<?php
namespace test;

use PHPUnit_Extensions_Selenium2TestCase;

class NovoLeilaoPage
{
    private $pagina;
    
    public function __construct(PHPUnit_Extensions_Selenium2TestCase $pagina)
    {
        $this->pagina = $pagina;
    }
    
    public function preenche($nome, $valor, $usuario, $usado)
    {
        
        $txtNome = $this->pagina->byName("leilao.nome");
        $txtValor = $this->pagina->byName("leilao.valorInicial");
        
        $txtNome->value($nome);
        $txtValor->value($valor);
        
        $select = $this->pagina->select(
            $this->pagina->byName("leilao.usuario.id")
        );
        
        $select->selectOptionByLabel($usuario);
        
        if($usado === true) {
            $ckUsado = $this->pagina->byName("leilao.usado");
            $ckUsado->click();
        }
        
        $txtNome->submit();
    }
    
    public function exibeErroDoNomeEDoValor()
    {
        return strpos($this->pagina->source(),"Nome obrigatorio!") !== false &&
            strpos($this->pagina->source(),"Nome obrigatorio!") !== false;
    }
}

