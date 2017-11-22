<?php
namespace src\br\com\caelum\leilao\servico;

use RuntimeException;
use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;

class Avaliador
{
    
    private $maiorDeTodos = - INF;
    
    private $menorDeTodos = INF;
    
    private $maiores;
    
    public function avalia(Leilao $leilao) {
        $lances = $leilao->getLances();
        
        if(count($lances) == 0)
            throw new RuntimeException("Não é possível avaliar um leilão sem lances");
        
        foreach($lances as $lance) {
            if($lance->getValor() > $this->maiorDeTodos) {
                $this->maiorDeTodos = $lance->getValor();
            }
            if($lance->getValor() < $this->menorDeTodos) {
                $this->menorDeTodos = $lance->getValor();
            }
        }
        $this->tresMaiores($leilao);
    }
    
    private function tresMaiores(Leilao $leilao) {
        $this->maiores = $leilao->getLances();
        
        usort($this->maiores, function(Lance $o1, Lance $o2) {
            if($o1->getValor() < $o2->getValor()) return 1;
            if($o1->getValor() > $o2->getValor()) return -1;
            return 0;
        });
            
            $this->maiores = array_slice($this->maiores,0, count($this->maiores) > 3 ? 3 : count($this->maiores));
    }
    
    public function getTresMaiores() : array {
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
}