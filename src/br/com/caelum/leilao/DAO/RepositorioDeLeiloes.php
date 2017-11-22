<?php
namespace src\br\com\caelum\leilao\DAO;

use src\br\com\caelum\leilao\dominio\Leilao;

interface RepositorioDeLeiloes {
    public function salva(Leilao $leilao);
    public function encerrados() : array;
    public function correntes() : array;
    public function atualiza(Leilao $leilao);
}
