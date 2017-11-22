<?php
namespace src\br\com\caelum\leilao\dominio;

use DateTime;

class Pagamento {
    
    private $valor;
    private $data;
    
    public function __construct(float $valor, DateTime $data) {
        $this->valor = $valor;
        $this->data = $data;
    }
    
    public function getValor() : float {
        return $this->valor;
    }
    
    public function getData() : DateTime {
        return $this->data;
    }
}