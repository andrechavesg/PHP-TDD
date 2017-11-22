<?php
namespace src\br\com\caelum\leilao\dominio;

use DateTime;

class RelogioDoSistema implements Relogio
{
    public function hoje() : DateTime{
        return new DateTime();
    }
}

