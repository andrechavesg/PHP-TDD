<?php
require_once ("vendor/autoload.php");

use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\servico\Avaliador;

$joao = new Usuario("Joao");

$leilao = new Leilao("Playstation 1 Usado");

$leiloeiro = new Avaliador();
$leiloeiro->avalia($leilao);

echo $leiloeiro->getMaiorLance()."\n";
echo $leiloeiro->getMenorLance();