<?php
namespace src\br\com\caelum\leilao\servico;

use DateInterval;
use DateTime;
use src\br\com\caelum\leilao\DAO\RepositorioDeLeiloes;
use src\br\com\caelum\leilao\dominio\Leilao;

class EncerradorDeLeilao
{

    private $total = 0;

    private $dao;

    public function __construct(RepositorioDeLeiloes $dao)
    {
        $this->dao = $dao;
    }

    public function encerra()
    {
        $todosLeiloesCorrentes = $this->dao->correntes();
        
        foreach ($todosLeiloesCorrentes as $leilao) {
            if ($this->comecouSemanaPassada($leilao)) {
                $leilao->encerra();
                $this->total ++;
                $this->dao->atualiza($leilao);
            }
        }
    }

    private function comecouSemanaPassada(Leilao $leilao)
    {
        return $this->diasEntre($leilao->getData(), new DateTime()) >= 7;
    }

    private function diasEntre(DateTime $inicio, DateTime $fim)
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