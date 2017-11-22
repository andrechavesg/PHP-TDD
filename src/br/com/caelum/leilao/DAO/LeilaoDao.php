<?php
namespace src\br\com\caelum\leilao\DAO;

use br\com\caelum\leilao\DAO\RepositorioDeLeiloes;
use br\com\caelum\leilao\dominio\Leilao;

class LeilaoDao implements RepositorioDeLeiloes
{

    private static $leiloes = array();

    public function salva(Leilao $leilao)
    {
        static::$leiloes[] = $leilao;
    }

    public function encerrados(): array
    {
        $filtrados = array();
        foreach (static::$leiloes as $leilao) {
            if ($leilao->isEncerrado())
                $filtrados[] = $leilao;
        }
        return $filtrados;
    }

    public function correntes(): array
    {
        $filtrados = array();
        foreach (static::$leiloes as $leilao) {
            if (! $leilao->isEncerrado())
                $filtrados[] = $leilao;
        }
        return $filtrados;
    }

    public function atualiza(Leilao $leilao)
    { /* faz nada! */}
}