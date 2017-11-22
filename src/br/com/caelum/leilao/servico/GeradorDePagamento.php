<?php
namespace br\com\caelum\leilao\servico;

use DateInterval;
use src\br\com\caelum\leilao\DAO\RepositorioDeLeiloes;
use src\br\com\caelum\leilao\DAO\RepositorioDePagamentos;
use src\br\com\caelum\leilao\dominio\Pagamento;
use src\br\com\caelum\leilao\dominio\Relogio;
use src\br\com\caelum\leilao\dominio\RelogioDoSistema;
use src\br\com\caelum\leilao\servico\Avaliador;

class GeradorDePagamento {
    
    private $pagamentos;
    private $leiloes;
    private $avaliador;
    private $relogio;
    
    public function __construct(RepositorioDeLeiloes $leiloes,
        RepositorioDePagamentos $pagamentos,
        Avaliador $avaliador,
        Relogio $relogio = null) {
            $this->leiloes = $leiloes;
            $this->pagamentos = $pagamentos;
            $this->avaliador = $avaliador;
            $this->relogio = $relogio ?? new RelogioDoSistema();
    }
    
    public function gera() : array {
        
        $leiloesEncerrados = $this->leiloes->encerrados();
        
        $novosPagamentos = array();
        
        foreach($leiloesEncerrados as $leilao) {
            $this->avaliador->avalia($leilao);
            
            $novoPagamento = new Pagamento($this->avaliador->getMaiorLance(), $this->primeiroDiaUtil());
            $novosPagamentos[] = $novoPagamento;
        }
        
        $this->pagamentos->salvaTodos($novosPagamentos);
        
        return $novosPagamentos;
    }
    
    private function primeiroDiaUtil() {
        $data = $this->relogio->hoje();
        $diaDaSemana = $data->format("w");
        
        if($diaDaSemana == 6) $data->add(new DateInterval("P2D"));
        else if($diaDaSemana == 0) $data->add(new DateInterval("P1D"));
        
        return $data;
    }
}