<?php
namespace src\br\com\caelum\leilao\dominio;

use DateTime;

interface Relogio
{
    public function hoje() : DateTime;
}