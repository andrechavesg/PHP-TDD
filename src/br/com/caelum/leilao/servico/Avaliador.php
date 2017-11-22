<?php
namespace src\br\com\caelum\leilao\servico;

use src\br\com\caelum\leilao\dominio\Leilao;

class Avaliador
{
    
    private $maiorDeTodos = - INF;
    
    private $menorDeTodos = INF;
    
    private $valorMedio;
    
    private $maiores;
    
    public function avalia(Leilao $leilao)
    {
        $lances = $leilao->getLances();
        
        if (empty($lances)) {
            $this->maiorDeTodos = 0;
            $this->menorDeTodos = 0;
            $this->valorMedio = 0;
        } else {
            foreach($lances as $lance) {
                if($lance->getValor() > $this->maiorDeTodos) $this->maiorDeTodos = $lance->getValor();
                if ($lance->getValor() < $this->menorDeTodos) $this->menorDeTodos = $lance->getValor();
            }
        }

        $this->pegaOsMaioresNo($leilao);
    }
    
    private function pegaOsMaioresNo(Leilao $leilao)
    {
        $this->maiores = $leilao->getLances();
        
        usort($this->maiores, function($a,$b) {
            if($a->getValor() < $b->getValor()) return 1;
            if($a->getValor() > $b->getValor()) return -1;
            return 0;
        });
        
        $this->maiores = array_slice($this->maiores,0, 3);
    }
    
    public function getTresMaiores()
    {
        return $this->maiores;
    }
    
    public function getMaiorLance(): float
    {
        return $this->maiorDeTodos;
    }
    
    public function getMenorLance(): float
    {
        return $this->menorDeTodos;
    }
    
    public function getValorMedio() : float
    {
        return $this->valorMedio;
    }
}
   
