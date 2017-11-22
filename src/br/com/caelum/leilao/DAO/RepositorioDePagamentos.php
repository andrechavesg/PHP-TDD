<?php
namespace src\br\com\caelum\leilao\DAO;

use src\br\com\caelum\leilao\dominio\Pagamento;


interface RepositorioDePagamentos {
    public function salva(Pagamento $pagamento);
    public function salvaTodos(array $pagamentos);
}