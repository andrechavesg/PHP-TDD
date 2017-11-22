<?php
namespace src\br\com\caelum\leilao\servico;

use DateInterval;
use DateTime;
use Exception;
use src\br\com\caelum\leilao\DAO\RepositorioDeLeiloes;
use src\br\com\caelum\leilao\dominio\Leilao;

class EncerradorDeLeilao
{

    private $total = 0;

    private $dao;
    private $carteiro;
    
    public function __construct(RepositorioDeLeiloes $dao, EnviadorDeEmail $carteiro)
    {
        $this->dao = $dao;
        $this->carteiro = $carteiro;
    }

    public function encerra()
    {
        $todosLeiloesCorrentes = $this->dao->correntes();
        
        foreach ($todosLeiloesCorrentes as $leilao) {
            try {
                if ($this->comecouSemanaPassada($leilao)) {
                    $leilao->encerra();
                    $this->total ++;
                    $this->dao->atualiza($leilao);
                    $this->carteiro->envia($leilao);
                }
            }
            catch(Exception $e) {
                // salvo a excecao no sistema de logs
                // e o loop continua!
            }
        }
    }

    private function comecouSemanaPassada(Leilao $leilao): bool
    {
        return $this->diasEntre($leilao->getData(), new DateTime()) >= 7;
    }

    private function diasEntre(DateTime $inicio, DateTime $fim): int
    {
        $data = clone $inicio;
        $diasNoIntervalo = 0;
        
        while ($data < $fim) {
            $data->add(new DateInterval('P1D'));
            $diasNoIntervalo ++;
        }
        return $diasNoIntervalo;
    }

    public function getTotalEncerrados(): int
    {
        return $this->total;
    }
}