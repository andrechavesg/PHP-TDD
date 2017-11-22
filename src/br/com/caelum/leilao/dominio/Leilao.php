<?php
namespace src\br\com\caelum\leilao\dominio;

use DateTime;

class Leilao
{

    private $descricao;

    private $lances = array();

    private $data;

    private $encerrado;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->data = new DateTime();
        $this->encerrado = false;
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

    public function getData() : DateTime
    {
        return $this->data;
    }

    public function setData(DateTime $data)
    {
        $this->data = $data;
    }
    
    public function isEncerrado()
    {
        return $this->encerrado;
    }

    public function encerra()
    {
        $this->encerrado = true;
    }

    private function ultimoLanceDado(): Lance
    {
        return $this->lances[count($this->lances) - 1];
    }

    private function qtdDelancesDo(Usuario $usuario): int
    {
        $total = 0;
        
        foreach ($this->lances as $l) {
            if ($l->getUsuario() == $usuario)
                $total ++;
        }
        
        return $total;
    }

    private function podeDarLance(Usuario $usuario): bool
    {
        return $this->ultimoLanceDado()->getUsuario() != $usuario && $this->qtdDelancesDo($usuario) < 5;
    }
}
