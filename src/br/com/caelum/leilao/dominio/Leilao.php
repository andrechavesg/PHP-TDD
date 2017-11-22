<?php
namespace src\br\com\caelum\leilao\dominio;

class Leilao
{

    private $descricao;

    private $lances = array();

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
    }

    /**
     *
     * @return mixed
     */
    public function getDescricao(): string
    {
        return $this->descricao;
    }

    /**
     *
     * @return multitype:
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    public function propoe(Lance $lance)
    {
        $this->lances[] = $lance;
    }
}
