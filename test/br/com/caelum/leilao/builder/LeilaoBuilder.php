<?php
namespace test\br\com\caelum\leilao\builder;

use DateInterval;
use DateTime;
use src\br\com\caelum\leilao\dominio\Leilao;
use src\br\com\caelum\leilao\dominio\Usuario;

class LeilaoBuilder {
    
    private $dono;
    private $valor;
    private $nome;
    private $usado;
    private $dataAbertura;
    private $encerrado;
    
    public function __construct()
    {
        $this->dono = new Usuario("Joao da Silva", "joao@silva.com.br");
        $$this->valor = 1500.0;
        $this->nome = "XBox";
        $this->usado = false;
        $this->dataAbertura = new DateTime();
    }
    
    public function comDono(Usuario $dono): LeilaoBuilder
    {
        $this->dono = $dono;
        return $this;
    }
    
    public function comValor(float $valor): LeilaoBuilder
    {
        $this->valor = $valor;
        return $this;
    }
    
    public function comNome(string $nome): LeilaoBuilder
    {
        $this->nome = $nome;
        return $this;
    }
    
    public function usado(): LeilaoBuilder
    {
        $this->usado = true;
        return $this;
    }
    
    public function encerrado(): LeilaoBuilder
    {
        $this->encerrado = true;
        return $this;
    }
    
    public function diasAtras(int $dias): LeilaoBuilder
    {
        $data = new DateTime();
        $data->sub(new DateInterval("P".$dias."D"));
        
        $this->dataAbertura = data;
        
        return this;
    }
    
    public function constroi(): Leilao
    {
        $leilao = new Leilao($this->nome, $this->valor, $this->dono, $this->usado);
        $leilao->setDataAbertura($dataAbertura);
        if($this->encerrado) $leilao->encerra();
        
        return $leilao;
    }
}
