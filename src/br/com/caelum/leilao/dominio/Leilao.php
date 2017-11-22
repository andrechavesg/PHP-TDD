<?php
namespace src\br\com\caelum\leilao\dominio;

class Leilao
{

    private $descricao;

    private $lances = array();

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getLances(): array
    {
        return $this->lances;
    }

    public function propoe(Lance $lance)
    {
        if (empty($this->lances) || $this->podeDarLance($lance->getUsuario())) {
            $this->lances[] = $lance;
        }
    }

    private function ultimoLanceDado()
    {
        return $this->lances[count($this->lances) - 1];
    }

    private function qtdDelancesDo(Usuario $usuario)
    {
        $total = 0;
        
        foreach ($this->lances as $l) {
            if ($l->getUsuario() == $usuario)
                $total ++;
        }
        
        return $total;
    }

    private function podeDarLance(Usuario $usuario)
    {
        return $this->ultimoLanceDado()->getUsuario() != $usuario 
            && $this->qtdDelancesDo($usuario) < 5;
    }
}
