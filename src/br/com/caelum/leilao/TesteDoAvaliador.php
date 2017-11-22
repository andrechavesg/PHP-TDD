<?php
require_once ("autoload.php");

use src\br\com\caelum\leilao\dominio\Lance;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;
use src\br\com\caelum\leilao\servico\Avaliador;

$joao = new Usuario("Joao");
$jose = new Usuario("José");
$maria = new Usuario("Maria");

$leilao = new Leilao("Playstation 3 Novo");

$leilao->propoe(new Lance($joao, 300.0));
$leilao->propoe(new Lance($jose, 400.0));
$leilao->propoe(new Lance($maria, 250.0));

$leiloeiro = new Avaliador();
$leiloeiro->avalia($leilao);

echo $leiloeiro->getMaiorLance();