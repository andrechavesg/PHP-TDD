<?php
namespace test;

use PHPUnit_Extensions_Selenium2TestCase;

class LeiloesPage
{
    private $pagina;
    
    public function __construct(PHPUnit_Extensions_Selenium2TestCase $pagina)
    {
        $this->pagina = $pagina;
    }
    
    public function visita()
    {
        $this->pagina->url("/leiloes");
    }
    
    public function novo()
    {
        // clica no link de novo leilao
        $this->pagina->byLinkText("Novo Leilão")->click();
        // retorna a classe que representa a nova pagina
        return new NovoLeilaoPage($this->pagina);
    }
    
    public function existe($produto, $valor, $usuario, $usado)
    {   
        return strpos($this->pagina->source(), $produto) !== false &&
        strpos($this->pagina->source(), strval($valor)) !== false &&
                strpos($this->pagina->source(), $usado ? "Sim" : "Não") !== false;
    }
    
    public function detalhes($posicao) {
        $elementos = $this->pagina->elements($this->pagina->using('link text')->value('exibir'));
            
        $elementos[$posicao-1]->click();
        
        return new DetalhesDoLeilaoPage($this->pagina);
    }
}

