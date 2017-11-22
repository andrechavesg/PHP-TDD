<?php
namespace src\br\com\caelum\leilao\dominio;

class Lance
{

    private $usuario;

    private $valor;

    public function __construct(Usuario $usuario, float $valor)
    {
        $this->usuario = $usuario;
        $this->valor = $valor;
    }

    /**
     *
     * @return Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     *
     * @return float
     */
    public function getValor(): float
    {
        return $this->valor;
    }
}

