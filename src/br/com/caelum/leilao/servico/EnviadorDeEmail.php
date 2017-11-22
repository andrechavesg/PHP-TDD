<?php
namespace br\com\caelum\leilao\servico;

use src\br\com\caelum\leilao\dominio\Leilao;

interface EnviadorDeEmail
{
    public function envia(Leilao $leilao);
}

