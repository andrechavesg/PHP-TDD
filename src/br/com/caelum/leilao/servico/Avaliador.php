<?php
namespace src\br\com\caelum\leilao\servico;

use src\br\com\caelum\leilao\dominio\Leilao;

class Avaliador
{
    
    private $maiorDeTodos = - INF;
    
    private $menorDeTodos = INF;
    
    private $valorMedio;
    
    public function avalia(Leilao $leilao) {
        $lances = $leilao->getLances();
        
        if (empty($lances)) {
            $this->maiorDeTodos = 0;
            $this->menorDeTodos = 0;
            $this->valorMedio = 0;
        } else {
            foreach($lances as $lance) {
                if($lance->getValor() > $this->maiorDeTodos) {
                    $this->maiorDeTodos = $lance->getValor();
                }
                if($lance->getValor() < $this->menorDeTodos) {
                    $this->menorDeTodos = $lance->getValor();
                }
                
                $this->valorMedio += $lance->getValor();
            }
            $this->valorMedio = $this->valorMedio/count($lances);
        }
    }
    
    public function getMaiorLance()
    {
        return $this->maiorDeTodos;
    }
    
    public function getMenorLance()
    {
        return $this->menorDeTodos;
    }
    
    public function getValorMedio()
    {
        return $this->valorMedio;
    }
}
