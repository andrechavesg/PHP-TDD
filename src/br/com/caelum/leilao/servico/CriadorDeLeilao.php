<?php
namespace src\br\com\caelum\leilao\servico;

use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;

class CriadorDeLeilao {
    
    private $leilao;
    
    public function para(string $descricao)
    {
        $this->leilao = new Leilao($descricao);
        return $this;
    }
    
    public function lance(Usuario $usuario, float $valor)
    {
        $this->leilao->propoe(new Lance($usuario, $valor));
        return $this;
    }
    
    public function naData($data)
    {
        $this->leilao->setData($data);
        return $this;
    }
    
    public function constroi()
    {
        return $this->leilao;
    }
}

